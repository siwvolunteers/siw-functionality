<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Config;
use SIW\Util;
use SIW\Data\Country;
use SIW\Data\Currency;
use SIW\Data\Language;
use SIW\Data\Plato\Project_Type as Plato_Project_Type;
use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;
use SIW\Facades\WooCommerce;
use SIW\Plato\Database\Projects\Row;
use SIW\Util\Logger;
use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Target_Audience;
use SIW\WooCommerce\Taxonomy_Attribute;

class Product {

	public const PUBLISH_STATUS = 'publish';
	public const REVIEW_STATUS = 'pending';

	protected bool $is_update = false;
	protected WC_Product_Project $product;
	protected Plato_Project_Type $project_type;
	protected Country $country;

	/**
	 * @var Language[]
	 */
	protected array $languages;

	/**
	 * @var Work_Type[]
	 */
	protected array $work_types;

	/**
	 * @var Sustainable_Development_Goal[];
	 */
	protected array $sustainable_development_goals;

	/**
	 * @var Target_Audience[]
	 */
	protected array $target_audiences = [];

	public function __construct( protected Row $plato_project, protected bool $force_update = false ) {
		add_filter( 'wc_product_has_unique_sku', '__return_false' );
		add_filter( 'wp_insert_post_data', [ $this, 'correct_post_slug' ], 10, 2 );
	}

	public function correct_post_slug( array $data, array $postarr ): array {
		if ( self::REVIEW_STATUS === $data['post_status'] && 'product' === $data['post_type'] ) {
			$data['post_name'] = $postarr['post_name'];
		}
		return $data;
	}

	public function process(): bool {
		if ( ! $this->set_project_type()
			|| ! $this->set_country()
			|| ! $this->set_work_types()
			|| ! $this->set_languages()
			|| ! $this->set_sustainable_development_goals()
		) {
			Logger::info( sprintf( 'Project met id %s wordt niet geïmporteerd', $this->plato_project->project_id ), __METHOD__ );
			return false;
		}

		$this->set_target_audiences();

		if ( empty( $this->country ) || empty( $this->work_types ) || empty( $this->plato_project->code ) ) {
			return false;
		}

		$product = WooCommerce::get_product_by_project_id( $this->plato_project->project_id );

		if ( is_a( $product, WC_Product_Project::class ) ) {
			$this->is_update = true;
			$this->product = $product;
			if ( ! $this->should_be_updated() ) {
				return false;
			}
		} else {
			// Niet importeren als het geen toegestane projectsoort is, we geen groepsprojecten in dit land aanbieden is of het project al begonnen is
			if ( ! $this->is_allowed_project_type() || ! $this->country->workcamps() || gmdate( 'Y-m-d' ) > $this->plato_project->start_date ) {
				return false;
			}
			$this->product = WooCommerce::get_product_object( WC_Product_Project::PRODUCT_TYPE );
		}

		$this->set_product();

		return true;
	}

	public function set_product() {
		$this->product->set_props(
			[
				// Default WooCommerce props
				'name'                       => $this->plato_project->name,
				'slug'                       => $this->get_slug(),
				'category_ids'               => $this->get_category_ids(),
				'attributes'                 => $this->get_attributes(),
				'sku'                        => $this->plato_project->code,
				'status'                     => $this->get_status(),
				'image_id'                   => $this->get_image_id(),

				// Extra props
				'checksum'                   => $this->get_checksum(),
				'project_id'                 => $this->plato_project->project_id,
				'latitude'                   => $this->plato_project->lat_project,
				'longitude'                  => $this->plato_project->lng_project,
				'start_date'                 => $this->plato_project->start_date,
				'end_date'                   => $this->plato_project->end_date,
				'min_age'                    => $this->plato_project->min_age,
				'max_age'                    => $this->plato_project->max_age,
				'participation_fee_currency' => $this->plato_project->participation_fee_currency,
				'participation_fee'          => $this->plato_project->participation_fee,
				'project_description'        => $this->get_project_description(),
			]
		);
		$this->product->save();
	}

	protected function set_project_type(): bool {
		$project_type = Plato_Project_Type::tryFrom( $this->plato_project->project_type );
		if ( null === $project_type ) {
			Logger::error( sprintf( 'Project type %s niet gevonden', $this->plato_project->project_type ), __METHOD__ );
			return false;
		}

		// TODO: lengte van project gebruiken om TEEN en FAM om te zetten naar STV/MTV/LTV
		if ( Plato_Project_Type::TEEN === $project_type || Plato_Project_Type::FAM === $project_type ) {
			$project_type = Plato_Project_Type::STV;
		}

		$this->project_type = $project_type;
		return true;
	}

	protected function set_country(): bool {
		$country_code = strtoupper( $this->plato_project->country );
		$country = Country::try_from_plato_code( $country_code );
		if ( null === $country ) {
			Logger::error( sprintf( 'Land met code %s niet gevonden', $country_code ), __METHOD__ );
			return false;
		}
		$this->country = $country;
		return true;
	}

	protected function set_languages(): bool {
		$this->languages = [];
		$languages = wp_parse_slug_list( $this->plato_project->languages );
		foreach ( $languages as $language_code ) {
			$language_code = strtoupper( $language_code );
			$language = Language::try_from_plato_code( $language_code );
			if ( null === $language ) {
				Logger::error( sprintf( 'Taal met code %s niet gevonden', $language_code ), __METHOD__ );
				return false;
			}
			$this->languages[] = $language;
		}
		return isset( $this->languages );
	}

	protected function set_work_types(): bool {
		$this->work_types = [];
		$work_types = wp_parse_slug_list( $this->plato_project->work );
		foreach ( $work_types as $work_type_code ) {
			$work_type_code = strtoupper( $work_type_code );
			$work_type = Work_Type::try_from_plato_code( $work_type_code );
			if ( null === $work_type ) {
				Logger::error( sprintf( 'Soort werk met code %s niet gevonden', $work_type_code ), __METHOD__ );
				return false;
			}
			$this->work_types[] = $work_type;
		}
		return isset( $this->work_types );
	}

	protected function set_sustainable_development_goals(): bool {
		$this->sustainable_development_goals = [];
		$goals = wp_parse_slug_list( $this->plato_project->sdg_prj );
		foreach ( $goals as $goal_slug ) {
			if ( '0' === $goal_slug ) {
				continue;
			}
			$goal = Sustainable_Development_Goal::tryFrom( (int) $goal_slug );
			if ( null === $goal ) {
				Logger::warning( sprintf( 'SDG met code %s niet gevonden', $goal_slug ), 'Importeren projecten' );
				return false;
			}
			$this->sustainable_development_goals[] = $goal;
		}
		return true;
	}

	protected function get_category_ids(): array {
		$category_ids = [];
		$category_id = Util::maybe_create_term( Taxonomy_Attribute::PROJECT_TYPE->value, $this->project_type->value, $this->project_type->label() );
		if ( false !== $category_id ) {
			$category_ids[] = $category_id;
		}
		return $category_ids;
	}

	protected function get_slug(): string {
		$year = gmdate( 'Y', strtotime( $this->plato_project->start_date ) );
		$code = $this->plato_project->code;
		$country = $this->country->label();
		$work = $this->work_types[0]->label();
		if ( count( $this->work_types ) > 1 ) {
			$work .= ' en ' . $this->work_types[1]->label();
		}
		return sanitize_title( sprintf( '%s-%s-%s-%s', $year, $code, $country, $work ) );
	}

	protected function get_attributes(): array {

		$attributes = [];

		// Product attributes
		$product_attributes = [
			[
				'attribute' => Product_Attribute::PROJECT_NAME,
				'value'     => $this->plato_project->name,
			],
			[
				'attribute' => Product_Attribute::PROJECT_CODE,
				'value'     => $this->plato_project->code,
			],
			[
				'attribute' => Product_Attribute::START_DATE,
				'value'     => gmdate( 'j-n-Y', strtotime( $this->plato_project->start_date ) ),
			],
			[
				'attribute' => Product_Attribute::END_DATE,
				'value'     => gmdate( 'j-n-Y', strtotime( $this->plato_project->end_date ) ),
			],
			[
				'attribute' => Product_Attribute::NUMBER_OF_VOLUNTEERS,
				'value'     => $this->format_number_of_volunteers(
					$this->plato_project->numvol,
					$this->plato_project->numvol_m,
					$this->plato_project->numvol_f
				),
			],
			[
				'attribute' => Product_Attribute::AGE_RANGE,
				'value'     => $this->format_age_range(
					$this->plato_project->min_age,
					$this->plato_project->max_age
				),
			],
			[
				'attribute' => Product_Attribute::PARTICIPATION_FEE,
				'value'     => $this->format_local_fee(
					$this->plato_project->participation_fee,
					$this->plato_project->participation_fee_currency
				),
			],
		];

		foreach ( $product_attributes as $product_attribute ) {
			if ( ! empty( $product_attribute['value'] ) ) {
				$attributes[ sanitize_title( $product_attribute['attribute']->label() ) ] = $this->create_product_attribute( $product_attribute['attribute'], $product_attribute['value'] );
			}
		}

		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::COUNTRY,
			'values'   => [
				$this->country->value => $this->country->label(),
			],
		];

		$work_type_values = [];
		foreach ( $this->work_types as $work_type ) {
			$work_type_values[ $work_type->value ] = $work_type->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::WORK_TYPE,
			'values'   => $work_type_values,
		];

		$language_values = [];
		foreach ( $this->languages as $language ) {
			$language_values[ $language->value ] = $language->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::LANGUAGE,
			'values'   => $language_values,
		];

		$month_slug = sanitize_title( siw_format_month( $this->plato_project->start_date, true ) );
		$month_name = ucfirst( siw_format_month( $this->plato_project->start_date, false ) );
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::MONTH,
			'visible'  => false,
			'values'   => [
				$month_slug => [
					'name'  => $month_name,
					'order' => gmdate( 'Ym', strtotime( $this->plato_project->start_date ) ),
				],
			],
		];

		$target_audience_values = [];
		foreach ( $this->target_audiences as $target_audience ) {
			$target_audience_values[ $target_audience->value ] = $target_audience->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::TARGET_AUDIENCE,
			'values'   => $target_audience_values,
		];

		$sdg_values = [];
		foreach ( $this->sustainable_development_goals as $goal ) {
			$sdg_values[ $goal->value ] = $goal->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::SDG,
			'values'   => $sdg_values,
		];

		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::CONTINENT,
			'values'   => [
				$this->country->continent()->value => $this->country->continent()->label(),
			],
		];

		foreach ( $taxonomy_attributes as $attribute ) {
			$attribute = wp_parse_args(
				$attribute,
				[
					'visible' => true,
					'values'  => [],
				]
			);

			if ( ! empty( $attribute['values'] ) ) {
				$attributes[ $attribute['taxonomy']->value ] = $this->create_taxonomy_attribute( $attribute['taxonomy'], $attribute['values'], $attribute['visible'] );
			}
		}
		return $attributes;
	}

	protected function create_product_attribute( Product_Attribute $product_attribute, $options, bool $visible = true ): \WC_Product_Attribute {
		$options = (array) $options;
		$attribute = new \WC_Product_Attribute();
		$attribute->set_name( $product_attribute->label() );
		$attribute->set_visible( $visible );
		$attribute->set_options( $options );
		return $attribute;
	}

	protected function create_taxonomy_attribute( Taxonomy_Attribute $taxonomy_attribute, array $values, bool $visible = true ): ?\WC_Product_Attribute {
		$wc_attribute_taxonomy_id = WooCommerce::attribute_taxonomy_id_by_name( $taxonomy_attribute->value );

		if ( 0 === $wc_attribute_taxonomy_id ) {
			Logger::warning(
				sprintf( 'Taxonomy %s bestaat niet', $taxonomy_attribute->value ),
				__METHOD__
			);

			return null;
		}

		foreach ( $values as $slug => $value ) {
			if ( is_array( $value ) ) {
				$name = $value['name'] ?? $slug;
				$order = $value['order'] ?? null;
			} else {
				$name = $value;
				$order = null;
			}
			$options[] = Util::maybe_create_term( "{$taxonomy_attribute->value}", (string) $slug, $name, $order );
		}

		$attribute = new \WC_Product_Attribute();
		$attribute->set_id( $wc_attribute_taxonomy_id );
		$attribute->set_options( $options );
		$attribute->set_name( $taxonomy_attribute->value );
		$attribute->set_visible( $visible );

		return $attribute;
	}

	protected function format_local_fee( float $fee, string $currency_code ): string {
		if ( 0.0 === $fee || empty( $currency_code ) ) {
			return '';
		}

		if ( Currency::EUR->value === $currency_code ) {
			return sprintf( '&euro; %s', $fee );
		}

		$currency = Currency::tryFrom( $currency_code );
		if ( null !== $currency ) {
			return sprintf( '%s %d (%s)', $currency->symbol(), $fee, $currency->label() );
		}

		return sprintf( '%s %d', $currency_code, $fee );
	}

	protected function format_age_range( int $min_age, int $max_age ): string {
		$min_age = $min_age > 0 ? $min_age : 18;
		$max_age = $max_age > 0 ? $max_age : 99;

		return sprintf(
		// translators: %1$d is de minimumleeftijd, %2$d is de maximumleeftijd
			__( '%1$d t/m %2$d jaar', 'siw' ),
			$min_age,
			$max_age
		);
	}

	protected function format_number_of_volunteers( int $numvol, int $numvol_m, int $numvol_f ): string {
		if ( ( $numvol_m + $numvol_f ) !== $numvol ) {
			return strval( $numvol );
		}

		return sprintf(
		// translators: aantal deelnemers bijv. '12 (6 mannen en 6 vrouwen)'
			__( '%1$d (%2$d %3$s en %4$d %5$s)', 'siw' ),
			$numvol,
			$numvol_m,
			_n( 'man', 'mannen', $numvol_m, 'siw' ),
			$numvol_f,
			_n( 'vrouw', 'vrouwen', $numvol_f, 'siw' )
		);
	}

	protected function get_project_description(): array {
		return [
			'description'           => $this->plato_project->description,
			'work'                  => $this->plato_project->descr_work,
			'accomodation_and_food' => $this->plato_project->descr_accomodation_and_food,
			'location_and_leisure'  => $this->plato_project->descr_location_and_leisure,
			'partner'               => $this->plato_project->descr_partner,
			'requirements'          => $this->plato_project->descr_requirements,
			'notes'                 => $this->plato_project->notes,
		];
	}

	protected function get_status(): string {
		if ( $this->is_update ) {
			return $this->product->get_status();
		}

		$status = self::PUBLISH_STATUS;
		foreach ( $this->work_types as $work_type ) {
			if ( $work_type->needs_review() ) {
				$status = self::REVIEW_STATUS;
			}
		}
		return $status;
	}

	protected function get_image_id(): ?int {
		$product_image = new Product_Image();

		$filename_base = sanitize_file_name(
			sprintf(
				'%s-%s',
				gmdate( 'Y', strtotime( $this->plato_project->start_date ) ),
				$this->plato_project->code
			)
		);

		// Afbreken indien afbeeldingen niet gedownload moeten worden of als er geen afbeeldingen zijn
		if ( ! Config::get_plato_download_images() || empty( $this->get_image_file_identifiers() ) ) {
			return null;
		}

		$image_id = $product_image->get_project_image(
			$this->get_image_file_identifiers(),
			$filename_base,
			$this->plato_project->project_id
		);
		if ( is_int( $image_id ) ) {
			$this->product->set_has_plato_image( true );
			return $image_id;
		}
		return null;
	}

	protected function get_image_file_identifiers(): array {
		return array_filter(
			[
				$this->plato_project->url_prj_photo1,
				$this->plato_project->url_prj_photo2,
				$this->plato_project->url_prj_photo3,
				$this->plato_project->url_prj_photo4,
				$this->plato_project->url_prj_photo5,
				$this->plato_project->url_prj_photo6,
				$this->plato_project->url_prj_photo7,
				$this->plato_project->url_prj_photo8,
				$this->plato_project->url_prj_photo9,
			]
		);
	}


	protected function get_checksum(): string {
		return hash( 'sha1', wp_json_encode( get_object_vars( $this->plato_project ) ) );
	}

	protected function should_be_updated(): bool {
		return (
			$this->force_update
			||
			$this->get_checksum() !== $this->product->get_checksum()
		);
	}

	protected function set_target_audiences() {
		if ( $this->plato_project->family || Plato_Project_Type::FAM === $this->project_type ) {
			$this->target_audiences[] = Target_Audience::FAMILIES;
		}
		if ( $this->plato_project->max_age <= 19 || Plato_Project_Type::TEEN === $this->project_type ) {
			$this->target_audiences[] = Target_Audience::TEENAGERS;
		}
	}

	protected function is_allowed_project_type(): bool {
		$allowed_project_types = [
			Plato_Project_Type::STV->value,
			Plato_Project_Type::TEEN->value,
			Plato_Project_Type::FAM->value,
			Plato_Project_Type::ESC->value,
		];
		return in_array( $this->project_type->value, $allowed_project_types, true );
	}
}
