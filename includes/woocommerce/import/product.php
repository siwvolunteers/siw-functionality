<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Config;
use SIW\Util;
use SIW\Data\Country;
use SIW\Data\Language;
use SIW\Data\Plato\Project as Plato_Project;
use SIW\Data\Plato\Project_Type as Plato_Project_Type;
use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;
use SIW\Util\Logger;
use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Target_Audience;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Import van een Groepsproject
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 *
 * @todo      splitsen t.b.v. onderhoudbaarheid
 */
class Product {

	/** Post-status van projecten die gepubliceerd kunnen worden */
	public const PUBLISH_STATUS = 'publish';

	/** Post-status van projecten die beoordeeld moeten worden */
	public const REVIEW_STATUS = 'pending';

	private const LOGGER_SOURCE = 'importeren-projecten';

	/** Geeft aan of het een update van een bestaand product is */
	protected bool $is_update = false;

	protected WC_Product_Project $product;

	protected Plato_Project_Type $project_type;

	protected Country $country;

	/**
	 * Projecttalen
	 *
	 * @var Language[]
	 */
	protected array $languages;

	/**
	 * Soort werk van het project
	 *
	 * @var Work_Type[]
	 */
	protected array $work_types;

	/**
	 * Sustainable Development Goals van het project
	 *
	 * @var Sustainable_Development_Goal[];
	 */
	protected array $sustainable_development_goals;

	/**
	 * Doelgroepen
	 *
	 * @var Target_Audience[]
	 */
	protected array $target_audiences = [];

	/** Constructor */
	public function __construct( protected Plato_Project $plato_project, protected bool $force_update = false ) {
		add_filter( 'wc_product_has_unique_sku', '__return_false' );
		add_filter( 'wp_insert_post_data', [ $this, 'correct_post_slug' ], 10, 2 );
	}

	/** Corrigeert slug van product als het ter review staat */
	public function correct_post_slug( array $data, array $postarr ): array {
		if ( self::REVIEW_STATUS === $data['post_status'] && 'product' === $data['post_type'] ) {
			$data['post_name'] = $postarr['post_name'];
		}
		return $data;
	}

	/** Verwerk item */
	public function process(): bool {

		/* Voorbereiden */
		if ( ! $this->set_project_type()
			|| ! $this->set_country()
			|| ! $this->set_work_types()
			|| ! $this->set_languages()
			|| ! $this->set_sustainable_development_goals()
		) {
			Logger::info( sprintf( 'Project met id %s wordt niet geïmporteerd', $this->plato_project->get_project_id() ), self::LOGGER_SOURCE );
			return false;
		}

		$this->set_target_audiences();

		if ( empty( $this->country ) || empty( $this->work_types ) || empty( $this->plato_project->get_code() ) ) {
			return false;
		}

		/* Zoek project */
		$product = siw_get_product_by_project_id( $this->plato_project->get_project_id() );

		if ( is_a( $product, WC_Product_Project::class ) ) {
			$this->is_update = true;
			$this->product = $product;
			if ( ! $this->should_be_updated() ) {
				return false;
			}
		} else {
			// Niet importeren als het geen toegestane projectsoort is, we geen groepsprojecten in dit land aanbieden is of het project al begonnen is
			if ( ! $this->is_allowed_project_type() || ! $this->country->has_workcamps() || gmdate( 'Y-m-d' ) > $this->plato_project->get_start_date() ) {
				return false;
			}
			$this->product = wc_get_product_object( WC_Product_Project::PRODUCT_TYPE );
		}

		$this->set_product();

		return true;
	}

	/** Zet de eigenschappen van het product */
	public function set_product() {
		$this->product->set_props(
			[
				// Default WooCommerce props
				'name'                       => $this->plato_project->get_name(),
				'slug'                       => $this->get_slug(),
				'category_ids'               => $this->get_category_ids(),
				'attributes'                 => $this->get_attributes(),
				'sku'                        => $this->plato_project->get_code(),
				'status'                     => $this->get_status(),
				'image_id'                   => $this->get_image_id(),

				// Extra props
				'checksum'                   => $this->plato_project->get_checksum(),
				'project_id'                 => $this->plato_project->get_project_id(),
				'latitude'                   => $this->plato_project->get_lat_project(),
				'longitude'                  => $this->plato_project->get_lng_project(),
				'start_date'                 => $this->plato_project->get_start_date(),
				'end_date'                   => $this->plato_project->get_end_date(),
				'min_age'                    => $this->plato_project->get_min_age(),
				'max_age'                    => $this->plato_project->get_max_age(),
				'participation_fee_currency' => $this->plato_project->get_participation_fee_currency(),
				'participation_fee'          => $this->plato_project->get_participation_fee(),
				'project_description'        => $this->get_project_description(),
			]
		);
		$this->product->save();
	}

	/** Zet project type */
	protected function set_project_type(): bool {
		$project_type = Plato_Project_Type::tryFrom( $this->plato_project->get_project_type() );
		if ( null === $project_type ) {
			Logger::error( sprintf( 'Project type %s niet gevonden', $this->plato_project->get_project_type() ), self::LOGGER_SOURCE );
			return false;
		}

		// TODO: lengte van project gebruiken om TEEN en FAM om te zetten naar STV/MTV/LTV
		if ( Plato_Project_Type::TEEN === $project_type || Plato_Project_Type::FAM === $project_type ) {
			$project_type = Plato_Project_Type::STV;
		}

		$this->project_type = $project_type;
		return true;
	}

	/** Zet land op basis van ISO-code */
	protected function set_country(): bool {
		$country_code = strtoupper( $this->plato_project->get_country() );
		$country = siw_get_country( $country_code, Country::PLATO_CODE );
		if ( ! is_a( $country, Country::class ) ) {
			Logger::error( sprintf( 'Land met code %s niet gevonden', $country_code ), self::LOGGER_SOURCE );
			return false;
		}
		$this->country = $country;
		return true;
	}

	/** Zet talen op basis van Plato-code */
	protected function set_languages(): bool {
		$this->languages = [];
		$languages = wp_parse_slug_list( $this->plato_project->get_languages() );
		foreach ( $languages as $language_code ) {
			$language_code = strtoupper( $language_code );
			$language = Language::try_from_plato_code( $language_code );
			if ( null === $language ) {
				Logger::error( sprintf( 'Taal met code %s niet gevonden', $language_code ), self::LOGGER_SOURCE );
				return false;
			}
			$this->languages[] = $language;
		}
		return isset( $this->languages );
	}

	/** Zet soorten werk op basis van Plato-code TODO: is niet gevonden een error en wat als er geen werk is? */
	protected function set_work_types(): bool {
		$this->work_types = [];
		$work_types = wp_parse_slug_list( $this->plato_project->get_work() );
		foreach ( $work_types as $work_type_code ) {
			$work_type_code = strtoupper( $work_type_code );
			$work_type = siw_get_work_type( $work_type_code, Work_Type::PLATO_CODE );
			if ( ! is_a( $work_type, Work_Type::class ) ) {
				Logger::error( sprintf( 'Soort werk met code %s niet gevonden', $work_type_code ), self::LOGGER_SOURCE );
				return false;
			}
			$this->work_types[] = $work_type;
		}
		return isset( $this->work_types );
	}

	/** Zet sustainable development goals */
	protected function set_sustainable_development_goals(): bool {
		$this->sustainable_development_goals = [];
		$goals = wp_parse_slug_list( $this->plato_project->get_sdg_prj() );
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

	/** Geeft de category (continent) van het project terug */
	protected function get_category_ids(): array {
		$continent = $this->country->get_continent();
		$category_ids = [];
		$continent_category_id = Util::maybe_create_term( Taxonomy_Attribute::CONTINENT->value, $continent->value, $continent->label() );
		if ( false !== $continent_category_id ) {
			$category_ids[] = $continent_category_id;
		}
		return $category_ids;
	}

	/**
	 * Zet de url-slug van het project
	 *
	 * Formaat: jaar-projectcode-land-werk
	 */
	protected function get_slug(): string {
		$year = gmdate( 'Y', strtotime( $this->plato_project->get_start_date() ) );
		$code = $this->plato_project->get_code();
		$country = $this->country->get_name();
		$work = $this->work_types[0]->get_name();
		if ( count( $this->work_types ) > 1 ) {
			$work .= ' en ' . $this->work_types[1]->get_name();
		}
		return sanitize_title( sprintf( '%s-%s-%s-%s', $year, $code, $country, $work ) );
	}

	/**
	 * Zet de eigenschappen van het project
	 *
	 * @todo splitsen
	 */
	protected function get_attributes(): array {

		$attributes = [];

		/* Product attributes */
		$product_attributes = [
			[
				'attribute' => Product_Attribute::PROJECT_NAME,
				'value'     => $this->plato_project->get_name(),
			],
			[
				'attribute' => Product_Attribute::PROJECT_CODE,
				'value'     => $this->plato_project->get_code(),
			],
			[
				'attribute' => Product_Attribute::START_DATE,
				'value'     => gmdate( 'j-n-Y', strtotime( $this->plato_project->get_start_date() ) ),
			],
			[
				'attribute' => Product_Attribute::END_DATE,
				'value'     => gmdate( 'j-n-Y', strtotime( $this->plato_project->get_end_date() ) ),
			],
			[
				'attribute' => Product_Attribute::NUMBER_OF_VOLUNTEERS,
				'value'     => siw_format_number_of_volunteers(
					$this->plato_project->get_numvol(),
					$this->plato_project->get_numvol_m(),
					$this->plato_project->get_numvol_f()
				),
			],
			[
				'attribute' => Product_Attribute::AGE_RANGE,
				'value'     => siw_format_age_range(
					$this->plato_project->get_min_age(),
					$this->plato_project->get_max_age()
				),
			],
			[
				'attribute' => Product_Attribute::PARTICIPATION_FEE,
				'value'     => siw_format_local_fee(
					$this->plato_project->get_participation_fee(),
					$this->plato_project->get_participation_fee_currency()
				),
			],
		];

		foreach ( $product_attributes as $product_attribute ) {
			if ( ! empty( $product_attribute['value'] ) ) {
				$attributes[ sanitize_title( $product_attribute['attribute']->label() ) ] = $this->create_product_attribute( $product_attribute['attribute'], $product_attribute['value'] );
			}
		}

		/* Land */
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::COUNTRY,
			'values'   => [
				$this->country->get_slug() => $this->country->get_name(),
			],
		];

		/* Werk */
		$work_type_values = [];
		foreach ( $this->work_types as $work_type ) {
			$work_type_values[ $work_type->get_slug() ] = $work_type->get_name();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::WORK_TYPE,
			'values'   => $work_type_values,
		];

		/* Taal */
		$language_values = [];
		foreach ( $this->languages as $language ) {
			$language_values[ $language->value ] = $language->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::LANGUAGE,
			'values'   => $language_values,
		];

		/* Maand */
		$month_slug = sanitize_title( siw_format_month( $this->plato_project->get_start_date(), true ) );
		$month_name = ucfirst( siw_format_month( $this->plato_project->get_start_date(), false ) );
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::MONTH,
			'visible'  => false,
			'values'   => [
				$month_slug => [
					'name'  => $month_name,
					'order' => gmdate( 'Ym', strtotime( $this->plato_project->get_start_date() ) ),
				],
			],
		];

		/* Doelgroepen */
		$target_audience_values = [];
		foreach ( $this->target_audiences as $target_audience ) {
			$target_audience_values[ $target_audience->value ] = $target_audience->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::TARGET_AUDIENCE,
			'values'   => $target_audience_values,
		];

		/* Sustainable development goals */
		$sdg_values = [];
		foreach ( $this->sustainable_development_goals as $goal ) {
			$sdg_values[ $goal->value ] = $goal->label();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::SDG,
			'values'   => $sdg_values,
		];

		// Projectsoort
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::PROJECT_TYPE,
			'values'   => [
				$this->project_type->value => $this->project_type->label(),
			],
		];

		// Attributes aanmaken
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

	/** Creëert product attribute */
	protected function create_product_attribute( Product_Attribute $product_attribute, $options, bool $visible = true ): \WC_Product_Attribute {
		$options = (array) $options;
		$attribute = new \WC_Product_Attribute();
		$attribute->set_name( $product_attribute->label() );
		$attribute->set_visible( $visible );
		$attribute->set_options( $options );
		return $attribute;
	}

	/** Creëert taxonomy attribute */
	protected function create_taxonomy_attribute( Taxonomy_Attribute $taxonomy_attribute, array $values, bool $visible = true ): ?\WC_Product_Attribute {

		$wc_attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy_attribute->value );

		// TODO: maybe_create_taxonomy
		if ( 0 === $wc_attribute_taxonomy_id ) {
			Logger::warning(
				sprintf( 'Taxonomy %s bestaat niet', $taxonomy_attribute->value ),
				self::LOGGER_SOURCE
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

	/** Geeft projectbeschrijving terug */
	protected function get_project_description(): array {
		return [
			'description'           => $this->plato_project->get_description(),
			'work'                  => $this->plato_project->get_descr_work(),
			'accomodation_and_food' => $this->plato_project->get_descr_accomodation_and_food(),
			'location_and_leisure'  => $this->plato_project->get_descr_location_and_leisure(),
			'partner'               => $this->plato_project->get_descr_partner(),
			'requirements'          => $this->plato_project->get_descr_requirements(),
			'notes'                 => $this->plato_project->get_notes(),
		];
	}

	/** Bepaalt de status van het project */
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

	/** Geeft id van featured afbeelding terug */
	protected function get_image_id(): ?int {
		$product_image = new Product_Image();

		$filename_base = sanitize_file_name(
			sprintf(
				'%s-%s',
				gmdate( 'Y', strtotime( $this->plato_project->get_start_date() ) ),
				$this->plato_project->get_code()
			)
		);

		// Afbreken indien afbeeldingen niet gedownload moeten worden of als er geen afbeeldingen zijn
		if ( ! Config::get_plato_download_images() || empty( $this->plato_project->get_image_file_identifiers() ) ) {
			return null;
		}

		$image_id = $product_image->get_project_image(
			$this->plato_project->get_image_file_identifiers(),
			$filename_base,
			$this->plato_project->get_project_id()
		);
		if ( is_int( $image_id ) ) {
			$this->product->set_has_plato_image( true );
			return $image_id;
		}
		return null;
	}

	/**
	 * Geeft aan of project bijgewerkt moet worden
	 *
	 * - Als meegegeven is dat het het project bijgewerkt moet worden
	 * - Als Plato-data veranderd is
	 */
	protected function should_be_updated(): bool {

		return (
			$this->force_update
			||
			$this->plato_project->get_checksum() !== $this->product->get_checksum()
		);
	}

	/**
	 * Zet speciale doelgroepen voor projecten
	 *
	 * @todo extra doelgroepen toevoegen / verplaatsen naar referentiegegevens /refactor
	 */
	protected function set_target_audiences() {

		if ( $this->plato_project->get_family() || Plato_Project_Type::FAM === $this->project_type ) {
			$this->target_audiences[] = Target_Audience::FAMILIES;
		}
		if ( $this->plato_project->get_max_age() <= 19 || Plato_Project_Type::TEEN === $this->project_type ) {
			$this->target_audiences[] = Target_Audience::TEENAGERS;
		}
	}

	/** Geeft aan het het een toegestaan type project is */
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
