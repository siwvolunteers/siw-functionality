<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Core\Template;
use SIW\Util;
use SIW\Data\Country;
use SIW\Data\Language;
use SIW\Data\Plato\Project as Plato_Project;
use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;
use SIW\Util\Logger;
use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Plato_Project_Type;
use SIW\WooCommerce\Project_Duration;
use SIW\WooCommerce\Target_Audience;
use SIW\WooCommerce\Taxonomy_Attribute;
use SIW\WooCommerce\WC_Product_Project;

/**
 * Import van een Groepsproject
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo      splitsen t.b.v. onderhoudbaarheid
 */
class Product {

	/** Post-status van projecten die gepubliceerd kunnen worden */
	const PUBLISH_STATUS = 'publish';

	/** Post-status van projecten die beoordeeld moeten worden */
	const REVIEW_STATUS = 'pending';

	/** Plato project */
	protected Plato_Project $plato_project;

	/** Forceer update van project */
	protected bool $force_update = false;

	/** Geeft aan of het een update van een bestaand product is */
	protected bool $is_update = false;

	/** Project */
	protected WC_Product_Project $product;

	/** Project type */
	protected Plato_Project_Type $project_type;

	/** Land van project */
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
	 * @var Target_Audience[];
	 * */
	protected array $target_audiences = [];

	/** Constructor */
	public function __construct( Plato_Project $plato_project, bool $force_update = false ) {
		add_filter( 'wc_product_has_unique_sku', '__return_false' );
		add_filter( 'wp_insert_post_data', [ $this, 'correct_post_slug'], 10, 2 );
		$this->plato_project = $plato_project;
		$this->force_update = $force_update;
	}

	/** Corrigeert slug van product als het ter review staat */
	public function correct_post_slug( array $data, array $postarr ): array {
		if ( self::REVIEW_STATUS == $data['post_status'] && 'product' == $data['post_type'] ) {
			$data['post_name'] = $postarr['post_name'];
		}
		return $data;
	}

	/** Verwerk item */
	public function process(): bool {

		/* Voorbereiden */
		if ( ! $this->set_project_type()
			|| ! $this->set_country()
			|| ! $this->set_languages()
			|| ! $this->set_work_types()
		) {
			Logger::info( sprintf( 'Project met id %s wordt niet geïmporteerd', $this->plato_project->get_project_id() ), 'importeren-projecten' );
			return false; //TODO: logging
		}

		$this->set_sustainable_development_goals();
		$this->set_target_audiences();

		/* Zoek project op basis van project id */
		$product = \siw_get_product_by_project_id( $this->plato_project->get_project_id() );

		if ( null !== $product ) {
			$this->is_update = true;
			$this->product = $product;
			if ( ! $this->should_be_updated() ) {
				return false;
			}
			
		}
		else {
			//Niet importeren als het geen toegestane projectsoort is, we geen groepsprojecten in dit land aanbieden is of het project al begonnen is
			if ( ! $this->is_allowed_project_type() || ! $this->country->has_workcamps() || date( 'Y-m-d' ) > $this->plato_project->get_start_date() ) {
				return false;
			}
			$this->product = new WC_Product_Project();
		}

		$this->set_product();

		return true;
	}

	/** Zet de eigenschappen van het product */
	public function set_product() {
		$this->product->set_props( [
			//Default WooCommerce props
			'name'                       => $this->plato_project->get_name(),
			'slug'                       => $this->get_slug(),
			'short_description'          => $this->get_short_description(),
			'category_ids'               => $this->get_category_ids(),
			'attributes'                 => $this->get_attributes(),
			'sku'                        => $this->plato_project->get_code(),
			'status'                     => $this->get_status(),
			'image_id'                   => $this->get_image_id(),

			//Extra props
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
			'project_description'        => $this->get_project_description() 
		]);
		$this->product->save();
	}

	/** Zet project type */
	protected function set_project_type(): bool {
		$project_type = Plato_Project_Type::tryFrom( $this->plato_project->get_project_type());
		if ( null == $project_type ) {
			Logger::error( sprintf( 'Project type %s niet gevonden', $this->plato_project->get_project_type() ), 'Importeren projecten' );
			return false;
		}
		$this->project_type = $project_type;
		return true;
	}

	/**
	 * Zet land op basis van Plato-code
	 */
	protected function set_country(): bool {
		$country_code = strtoupper( $this->plato_project->get_country() );
		$country = siw_get_country( $country_code, Country::PLATO_CODE );
		if ( ! is_a( $country, Country::class ) ) {
			Logger::error( sprintf( 'Land met code %s niet gevonden', $country_code ), 'Importeren projecten' );
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
			$language = siw_get_language( $language_code, Language::PLATO_CODE );
			if (  ! is_a( $language, Language::class ) ) {
				Logger::error( sprintf( 'Taal met code %s niet gevonden', $language_code ), 'Importeren projecten' );
				return false;
			}
			$this->languages[] = $language;
		}
		return true;
	}

	/** Zet soorten werk op basis van Plato-code */
	protected function set_work_types(): bool {
		$this->work_types = [];
		$work_types = wp_parse_slug_list( $this->plato_project->get_work() );
		foreach ( $work_types as $work_type_code ) {
			$work_type_code = strtoupper( $work_type_code );
			$work_type = siw_get_work_type( $work_type_code, Work_Type::PLATO_CODE );
			if ( ! is_a( $work_type, Work_Type::class ) ) {
				Logger::error( sprintf( 'Soort werk met code %s niet gevonden', $work_type_code ), 'Importeren projecten' );
				return false;
			}
			$this->work_types[] = $work_type;
		}
		return true;
	}

	/** Zet sustainable development goals */
	protected function set_sustainable_development_goals(): bool {
		$this->sustainable_development_goals = [];
		$goals = wp_parse_slug_list( $this->plato_project->get_sdg_prj() );
		foreach ( $goals as $goal_slug ) {
				//continue als slug = 0

			$goal = siw_get_sustainable_development_goal( $goal_slug );
			if ( ! is_a( $goal, Sustainable_Development_Goal::class ) ) {
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
		if ( $category_id = Util::maybe_create_term( Taxonomy_Attribute::CONTINENT()->value, $continent->get_slug(), $continent->get_name() ) ) {
			$category_ids[] = $category_id;
		}
		return $category_ids;
	}

	/**
	 * Zet de url-slug van het project
	 * 
	 * Formaat: jaar-projectcode-land-werk
	 */
	protected function get_slug() : string {
		$year = date( 'Y', strtotime( $this->plato_project->get_start_date() ) );
		$code = $this->plato_project->get_code();
		$country = $this->country->get_name();
		$work = $this->work_types[0]->get_name();
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
			Product_Attribute::PROJECT_NAME()->label         => $this->plato_project->get_name(),
			Product_Attribute::PROJECT_CODE()->label         => $this->plato_project->get_code(),
			Product_Attribute::START_DATE()->label           => date( 'j-n-Y', strtotime( $this->plato_project->get_start_date() ) ),
			Product_Attribute::END_DATE()->label             => date( 'j-n-Y', strtotime( $this->plato_project->get_end_date() ) ),
			Product_Attribute::NUMBER_OF_VOLUNTEERS()->label => siw_format_number_of_volunteers(
				$this->plato_project->get_numvol(),
				$this->plato_project->get_numvol_m(),
				$this->plato_project->get_numvol_f()
			),
			Product_Attribute::AGE_RANGE()->label             => siw_format_age_range(
				$this->plato_project->get_min_age(),
				$this->plato_project->get_max_age()
			),
			Product_Attribute::PARTICIPATION_FEE()->label      => siw_format_local_fee(
				$this->plato_project->get_participation_fee(),
				$this->plato_project->get_participation_fee_currency()
			),
		];

		foreach ( $product_attributes as $attribute => $values ) {
			if ( ! empty( $values ) ) {
				$attributes[ sanitize_title( $attribute )] = $this->create_product_attribute( $attribute, $values );
			}
		}

		/** Projectduur */
		$start_date_time      = new \DateTime( $this->plato_project->get_start_date() );
		$end_date_time        = new \DateTime( $this->plato_project->get_end_date() );
			
		$duration = $start_date_time->diff( $end_date_time )->days;
		if ( $duration < 30 ) { //TODO: magic number vervangen?
			$project_duration = Project_Duration::STV();
		} elseif ( $duration < 90 ) { //TODO: magic number vervangen?
			$project_duration = Project_Duration::MTV();
		} else {
			$project_duration = Project_Duration::LTV();
		}

		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::DURATION(),
			'visible'  => false,
			'values'   => [
				$project_duration->value => $project_duration->label
			]
		];

		/* Land */
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::COUNTRY(),
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
			'taxonomy' => Taxonomy_Attribute::WORK_TYPE(),
			'values'   => $work_type_values,
		];

		/* Taal */
		$language_values = [];
		foreach ( $this->languages as $language ) {
			$language_values[ $language->get_slug() ] = $language->get_name();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::LANGUAGE(),
			'values'   => $language_values,
		];

		
		/* Maand */
		$month_slug = sanitize_title( siw_format_month( $this->plato_project->get_start_date(), true ) );
		$month_name = ucfirst( siw_format_month( $this->plato_project->get_start_date(), false ) );
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::MONTH(),
			'visible'  => false,
			'values'   => [
				$month_slug => [
					'name'  => $month_name,
					'order' => date( 'Ym', strtotime( $this->plato_project->get_start_date() ) ),
				],
			],
		];

		/* Doelgroepen */
		$target_audience_values = [];
		foreach ( $this->target_audiences as $target_audience ) {
			$target_audience_values[ $target_audience->value ] = $target_audience->label;
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::TARGET_AUDIENCE(),
			'values'   => $target_audience_values,
		];

		/* Sustainable development goals */
		$sdg_values = [];
		foreach ( $this->sustainable_development_goals as $goal ) {
			$sdg_values[$goal->get_slug()] = $goal->get_full_name();
		}
		$taxonomy_attributes[] = [
			'taxonomy' => Taxonomy_Attribute::SDG(),
			'values'   => $sdg_values,
		];

		//Attributes aanmaken
		foreach ( $taxonomy_attributes as $attribute ) {
			$attribute = wp_parse_args(
				$attribute,
				[
					'visible'   => true,
					'values'    => [],
				]
			);

			if ( ! empty( $attribute['values'] ) ) {
				$attributes[ $attribute['taxonomy']->value ] = $this->create_taxonomy_attribute( $attribute['taxonomy'], $attribute['values'], $attribute['visible'] );
			}
		}
		return $attributes;
	}

	/** Creëert product attribute */
	protected function create_product_attribute( string $name, $options, bool $visible = true ): \WC_Product_Attribute {
		$options = (array) $options;
		$attribute = new \WC_Product_Attribute;
		$attribute->set_name( $name );
		$attribute->set_visible( $visible );
		$attribute->set_options( $options );
		return $attribute;
	}

	/** Creëert taxonomy attribute */
	protected function create_taxonomy_attribute( Taxonomy_Attribute $taxonomy, array $values, bool $visible = true ): ?\WC_Product_Attribute {

		$wc_attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy->value );

		//TODO: maybe_create_taxonomy
		if ( 0 === $wc_attribute_taxonomy_id ) {
			$wc_attribute_taxonomy_id = wc_create_attribute(
				[
					'name'         => $taxonomy->label,
					'slug'         => $taxonomy->value,
					'type'         => 'select',
					'order_by'     => 'name',
					'has_archives' => true,
				]
			);
			if ( is_wp_error( $wc_attribute_taxonomy_id ) ) {
				return null;
			}
		}

		foreach ( $values as $slug => $value ) {
			if ( is_array( $value ) ) {
				$name = $value['name'] ?? $slug;
				$order = $value['order'] ?? null;
			}
			else {
				$name = $value;
				$order = null;
			}
			$options[] = Util::maybe_create_term( "{$taxonomy->value}", (string) $slug, $name, $order );
		}

		$attribute = new \WC_Product_Attribute;
		$attribute->set_id( $wc_attribute_taxonomy_id );
		$attribute->set_options( $options );
		$attribute->set_name( $taxonomy->value );
		$attribute->set_visible( $visible );

		return $attribute;
	}

	/** Parset beschrijvingen */
	protected function parse_description( string $template ): string {
		$context = [
			'project_type' => $this->get_workcamp_type(),
			'country'      => $this->country->get_name(),
			'dates'        => siw_format_date_range(
				$this->plato_project->get_start_date(),
				$this->plato_project->get_end_date(),
				false
			),
			'participants' => $this->plato_project->get_numvol(),
			'ages'         => siw_format_age_range(
				$this->plato_project->get_min_age(),
				$this->plato_project->get_max_age()
			),
			'work_type'    => strtolower( $this->work_types[0]->get_name() ),
		];
		return Template::parse_string_template( $template, $context );
	}

	/**
	 * Geneert de korte (Nederlandse) beschrijving van een project op basis van een template
	 */
	protected function get_short_description(): string {
		$templates = siw_get_data( 'workcamps/description-templates' );
		$template = implode( SPACE, $templates[ array_rand( $templates, 1 ) ]  );

		return $this->parse_description( $template );
	}

	/** Zet meta properties van product */
	protected function get_project_description(): array {
		return [
				'description'              => $this->plato_project->get_description(),
				'work'                     => $this->plato_project->get_descr_work(),
				'accomodation_and_food'    => $this->plato_project->get_descr_accomodation_and_food(),
				'location_and_leisure'     => $this->plato_project->get_descr_location_and_leisure(),
				'partner'                  => $this->plato_project->get_descr_partner(),
				'requirements'             => $this->plato_project->get_descr_requirements(),
				'notes'                    => $this->plato_project->get_notes(),
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
	protected function get_image_id() : ?int {
		$product_image = new Product_Image();

		$filename_base = sanitize_file_name(
			sprintf(
				'%s-%s',
				date( 'Y', strtotime( $this->plato_project->get_start_date() ) ),
				$this->plato_project->get_code()
			)
		);

		// Probeer Plato-afbeelding op te halen ( indien van toepassing )
		if (
			siw_get_option( 'plato.download_images' ) &&
			! empty( $this->plato_project->get_image_file_identifiers() ) &&
			! $this->product->use_stockfoto()
		) {
			$image_id = $product_image->get_project_image(
				$this->plato_project->get_image_file_identifiers(),
				$filename_base,
				$this->plato_project->get_project_id()
			);
			if ( is_int( $image_id ) ) {
				$this->product->set_has_plato_image( true );
				return $image_id;
			}
		}

		// Als dat niet gelukt is probeer dan een stockfoto te vinden die bij het project past
		$image_id = $product_image->get_stock_image( $this->country, $this->work_types );
		
		return $image_id;
	}

	/**
	 * Geeft aan of project bijgewerkt moet worden
	 * 
	 * - Als meegegeven is dat het het project bijgewerkt moet worden
	 * - Als Plato-data veranderd is
	 * - Bij geforceerde volledige update
	 */
	protected function should_be_updated() : bool {
		return (
			$this->force_update
			||
			$this->plato_project->get_checksum() != $this->product->get_checksum()
			||
			siw_get_option( 'plato.force_full_update' ) 
		);
	}

	/** Zet speciale doelgroepen voor projecten */
	protected function set_target_audiences() {
		if ( $this->plato_project->get_family() || $this->project_type->equals( Plato_Project_Type::FAM() ) ) {
			$this->target_audiences[] = Target_Audience::FAMILIES();
		}
		if ( $this->plato_project->get_max_age() <= 19 || $this->project_type->equals( Plato_Project_Type::TEEN() ) ) {
			$this->target_audiences[] = Target_Audience::TEENAGERS();
		}
	}

	/** Geeft type groepsproject terug TODO: project type gebruiken */
	protected function get_workcamp_type(): string {
		
		if ( Target_Audience::FAMILIES()->equals( ...$this->target_audiences ) ) {
			$workcamp_type = 'familieproject';
		}
		elseif ( Target_Audience::TEENAGERS()->equals( ...$this->target_audiences ) ) {
			$workcamp_type = 'tienerproject';
		}
		else {
			$workcamp_type = 'project';
		}
		return $workcamp_type;
	}

	/** Geeft aan het het een toegestaan type project is */
	protected function is_allowed_project_type() : bool {
		$allowed_project_types = [
			Plato_Project_Type::STV(),
			Plato_Project_Type::TEEN(),
			Plato_Project_Type::FAM(),
		];
		return $this->project_type->equals( ...$allowed_project_types );
	}
}
