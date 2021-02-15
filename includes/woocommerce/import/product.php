<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\Formatting;
use SIW\Util;
use SIW\Data\Country;
use SIW\Data\Language;
use SIW\Data\Plato\Project as Plato_Project;
use SIW\Data\Sustainable_Development_Goal;
use SIW\Data\Work_Type;

/**
 * Import van een Groepsproject
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo      splitsen t.b.v. onderhoudbaarheid
 */
class Product {

	/**
	 * Post-status van projecten die beoordeeld moeten worden
	 */
	const REVIEW_STATUS = 'pending';

	/** Plato project */
	protected Plato_Project $plato_project;

	/** Geeft aan of het een update van een bestaand product is */
	protected bool $is_update = false;

	/** Project */
	protected \WC_Product $product;

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

	/** Tarieven die van toepassing zijn */
	protected array $tariffs;

	/** Doelgroepen */
	protected array $target_audiences = [];

	/** Constructor */
	public function __construct( Plato_Project $plato_project ) {
		add_filter( 'wc_product_has_unique_sku', '__return_false' );
		add_filter( 'wp_insert_post_data', [ $this, 'correct_post_slug'], 10, 2 );
		$this->plato_project = $plato_project;
	}

	/** Corrigeert slug van product als het ter review staat */
	public function correct_post_slug( array $data, array $postarr ) : array {
		if ( self::REVIEW_STATUS == $data['post_status'] && 'product' == $data['post_type'] ) {
			$data['post_name'] = $postarr['post_name'];
		}
		return $data;
	}

	/**
	 * Verwerk item
	 * 
	 * @todo logging als land/werk/code leeg is
	 */
	public function process() : bool {

		/* Voorbereiden */
		$this->set_country();
		$this->set_languages();
		$this->set_work_types();
		$this->set_sustainable_development_goals();
		$this->set_target_audiences();
		$this->set_tariffs();

		if ( empty( $this->country ) || empty( $this->work_types ) || empty( $this->plato_project->get_code() ) ) {
			return false;
		}

		/* Zoek project */
		$args = [
			'project_id' => $this->plato_project->get_project_id(),
			'return'     => 'objects',
			'limit'      => -1,
		];
		$products = wc_get_products( $args );

		if ( ! empty( $products ) ) {
			$this->is_update = true;
			$this->product = $products[0];
			if ( ! $this->should_be_updated() ) {
				return false;
			}
			
		}
		else {
			//Niet importeren als het geen groepsproject is, niet in een toegestaan land is of al begonnen is
			if ( ! $this->is_allowed_project_type() || ! $this->country->is_allowed() || date( 'Y-m-d' ) > $this->plato_project->get_start_date() ) {
				return false;
			}
			$this->product = new \WC_Product_Variable;
		}

		$this->set_product();

		//Variaties bijwerken (indien nodig) en creëren
		$variations = new Product_Variations( $this->product, $this->tariffs );
		if ( $this->is_update ) {
			$variations->update();
		}
		$variations->create();

		return true;
	}

	/** Zet de eigenschappen van het product */
	public function set_product() {
		$this->product->set_props( [
			'name'               => $this->get_name(),
			'slug'               => $this->get_slug(),
			'short_description'  => $this->get_short_description(),
			'category_ids'       => $this->get_category_ids(),
			'attributes'         => $this->get_attributes(),
			'default_attributes' => $this->get_default_attributes(),
			'sku'                => $this->plato_project->get_code(),
			'sold_individually'  => true,
			'virtual'            => true,
			'status'             => $this->get_status(),
			'image_id'           => $this->get_image_id(),
			'xml'                => null, //Legacy, kan uiteindelijk weg

		]);
		foreach ( $this->get_meta_data() as $key => $value ) {
			if ( ! empty( $value ) ) {
				$this->product->update_meta_data( $key, $value );
			}
		}
		$this->product->save();
	}

	/**
	 * Zet land op basis van ISO-code
	 */
	protected function set_country() {
		$country_code = strtoupper( $this->plato_project->get_country() );
		$country = siw_get_country( $country_code, 'iso' );
		if ( is_a( $country, Country::class ) ) {
			$this->country = $country;
		}
	}

	/** Zet talen op basis van Plato-code */
	protected function set_languages() {
		$this->languages = [];
		$languages = wp_parse_slug_list( $this->plato_project->get_languages() );
		foreach ( $languages as $language_code ) {
			$language_code = strtoupper( $language_code );
			$language = siw_get_language( $language_code, 'plato_code' );
			if ( is_a( $language, Language::class ) ) {
				$this->languages[] = $language;
			}
		}
	}

	/** Zet soorten werk op basis van Plato-code */
	protected function set_work_types() {
		$this->work_types = [];
		$work_types = wp_parse_slug_list( $this->plato_project->get_work() );
		foreach ( $work_types as $work_type_code ) {
			$work_type_code = strtoupper( $work_type_code );
			$work_type = siw_get_work_type( $work_type_code, 'plato_code' );
			if ( is_a( $work_type, Work_Type::class ) ) {
				$this->work_types[] = $work_type;
			}
		}
	}

	/** Zet sustainable development goals */
	protected function set_sustainable_development_goals() {
		$this->sustainable_development_goals = [];
		$goals = wp_parse_slug_list( $this->plato_project->get_sdg_prj() );
		foreach ( $goals as $goal_slug ) {
			$goal = siw_get_sustainable_development_goal( $goal_slug );
			if ( is_a( $goal, Sustainable_Development_Goal::class ) ) {
				$this->sustainable_development_goals[] = $goal;
			}
		}
	}

	/** Geeft de category (continent) van het project terug */
	protected function get_category_ids() : array {
		$continent = $this->country->get_continent();
		$category_ids = [];
		if ( $category_id = Util::maybe_create_term( 'product_cat', $continent->get_slug(), $continent->get_name() ) ) {
			$category_ids[] = $category_id;
		}
		return $category_ids;
	}

	/** Geeft naam van het project terug */
	protected function get_name() : string {
		$country = $this->country->get_name();
		$work_types = array_slice( $this->work_types, 0, 2 );
		if ( 1 === count( $work_types ) ) {
			$work = $work_types[0]->get_name();
		}
		else {
			$work = sprintf( '%s en %s', $work_types[0]->get_name(), strtolower( $work_types[1]->get_name() ) );
		}
		return sprintf( '%s | %s', $country, ucfirst( $work ) );
	}

	/**
	 * Zet de url-slug van het project
	 * 
	 * Formaat: jaar-projectcode-projectnaam
	 */
	protected function get_slug() : string {
		$year = date( 'Y', strtotime( $this->plato_project->get_start_date() ) );
		$code = $this->plato_project->get_code();
		$name = $this->get_name();
		return sanitize_title( sprintf( '%s-%s-%s', $year, $code, $name ) );
	}

	/**
	 * Zet de eigenschappen van het project
	 * 
	 * @todo splitsen
	 */
	protected function get_attributes() : array {

		$attributes = [];

		/* Product attributes */
		$product_attributes = [
			'Projectnaam'          => $this->plato_project->get_name(),
			'Projectcode'          => $this->plato_project->get_code(),
			'Startdatum'           => date( 'j-n-Y', strtotime( $this->plato_project->get_start_date() ) ),
			'Einddatum'            => date( 'j-n-Y', strtotime( $this->plato_project->get_end_date() ) ),
			'Aantal vrijwilligers' => Formatting::format_number_of_volunteers(
				$this->plato_project->get_numvol(),
				$this->plato_project->get_numvol_m(),
				$this->plato_project->get_numvol_f()
			),
			'Leeftijd'             => Formatting::format_age_range(
				$this->plato_project->get_min_age(),
				$this->plato_project->get_max_age()
			),
			'Lokale bijdrage'      => Formatting::format_local_fee(
				$this->plato_project->get_participation_fee(),
				$this->plato_project->get_participation_fee_currency()
			),
		];

		foreach ( $product_attributes as $attribute => $values ) {
			if ( ! empty( $values ) ) {
				$attributes[ sanitize_title( $attribute )] = $this->create_product_attribute( $attribute, $values );
			}
		}

		/* Land */
		$taxonomy_attributes['land']['values'][] = [
			'slug' => $this->country->get_slug(),
			'name' => $this->country->get_name(),
		];

		/* Werk */
		foreach ( $this->work_types as $work_type ) {
			$taxonomy_attributes['soort-werk']['values'][] = [
				'slug' => $work_type->get_slug(),
				'name' => $work_type->get_name(),
			];
		}

		/* Taal */
		foreach ( $this->languages as $language ) {
			$taxonomy_attributes['taal']['values'][] = [
				'slug' => $language->get_slug(),
				'name' => $language->get_name(),
			];
		}
		
		/* Maand */
		$month_slug = sanitize_title( Formatting::format_month( $this->plato_project->get_start_date(), true ) );
		$month_name = ucfirst( Formatting::format_month( $this->plato_project->get_start_date(), false ) );
		$taxonomy_attributes['maand']['visible'] = false;
		$taxonomy_attributes['maand']['values'][] = [
			'slug'  => $month_slug,
			'name'  => $month_name,
			'order' => date( 'Ym', strtotime( $this->plato_project->get_start_date() ) ),
		];

		/* Tarieven */
		$taxonomy_attributes['tarief']['visible'] = false;
		$taxonomy_attributes['tarief']['variation'] = true;
		foreach ( $this->tariffs as $slug => $tariff ) {
			$taxonomy_attributes['tarief']['values'][] = [
				'slug' => $slug,
				'name' => $tariff['name'],
			];
		}

		/* Doelgroepen */
		foreach ( $this->target_audiences as $target_audience ) {
			$taxonomy_attributes['doelgroep']['values'][] = [
				'slug' => $target_audience['slug'],
				'name' => $target_audience['name'],
			];
		}

		/* Sustainable development goals */
		foreach ( $this->sustainable_development_goals as $goal ) {
			$taxonomy_attributes['sdg']['values'][] = [
				'slug' => $goal->get_slug(),
				'name' => $goal->get_full_name(),
			];
		}

		//Attributes aanmaken
		foreach ( $taxonomy_attributes as $taxonomy => $attribute ) {
			$attribute = wp_parse_args(
				$attribute,
				[
					'visible'   => true,
					'variation' => false,
					'values'    => [],
				]
			);

			if ( ! empty( $attribute['values'] ) ) {
				$attributes["pa_{$taxonomy}"] = $this->create_taxonomy_attribute( $taxonomy, $attribute['values'], $attribute['visible'], $attribute['variation'] );
			}
		}
		return $attributes;
	}

	/** Geeft default eigenschappen terug */
	protected function get_default_attributes() : array {
		$max_age = $this->plato_project->get_max_age();
		$default_tariff = ( 18 > $max_age ) ? 'student' : 'regulier';
		return [ 'pa_tarief' => $default_tariff ];
	}

	/** Creëert product attribute */
	protected function create_product_attribute( string $name, $options, bool $visible = true ) : \WC_Product_Attribute {
		$options = (array) $options;
		$attribute = new \WC_Product_Attribute;
		$attribute->set_name( $name );
		$attribute->set_visible( $visible );
		$attribute->set_options( $options );
		return $attribute;
	}

	/** Creëert taxonomy attribute */
	protected function create_taxonomy_attribute( string $taxonomy, $values, bool $visible = true, bool $variation = false ) : ?\WC_Product_Attribute {

		$wc_attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy );

		//TODO: maybe_create_taxonomy
		if ( 0 === $wc_attribute_taxonomy_id ) {
			$wc_attribute_taxonomy_id = wc_create_attribute(
				[
					'name'         => $taxonomy, //TODO: juiste naam gebruiken
					'slug'         => $taxonomy,
					'type'         => 'select',
					'order_by'     => 'name', //TODO: juiste waarde gebruiken
					'has_archives' => true,
				]
			);
			if ( is_wp_error( $wc_attribute_taxonomy_id ) ) {
				return null;
			}
		}

		foreach ( $values as $value ) {
			$order = $value['order'] ?? null;
			$options[] = Util::maybe_create_term( "pa_{$taxonomy}", $value['slug'], $value['name'], $order );
		}

		$attribute = new \WC_Product_Attribute;
		$attribute->set_id( $wc_attribute_taxonomy_id );
		$attribute->set_options( $options );
		$attribute->set_name( "pa_{$taxonomy}" );
		$attribute->set_visible( $visible );
		$attribute->set_variation( $variation );

		return $attribute;
	}

	/** Parset beschrijvingen */
	protected function parse_description( string $template ) : string {
		$vars = [
			'project_type' => $this->get_workcamp_type(),
			'country'      => $this->country->get_name(),
			'dates'        => Formatting::format_date_range(
				$this->plato_project->get_start_date(),
				$this->plato_project->get_end_date(),
				false
			),
			'participants' => $this->plato_project->get_numvol(),
			'ages'         => Formatting::format_age_range(
				$this->plato_project->get_min_age(),
				$this->plato_project->get_max_age()
			),
			'work_type'    => strtolower( $this->work_types[0]->get_name() ),
		];
		return Formatting::parse_template( $template, $vars );
	}

	/**
	 * Geneert de korte (Nederlandse) beschrijving van een project op basis van een template
	 * 
	 * @todo aparte functie in Formatting
	 */
	protected function get_short_description() : string {
		$templates = siw_get_data( 'workcamps/description-templates' );
		$template = implode( SPACE, $templates[ array_rand( $templates, 1 ) ]  );

		return $this->parse_description( $template );
	}

	/** Zet meta properties van product */
	protected function get_meta_data() : array {
		$meta_data = [
			'checksum'                   => $this->plato_project->get_checksum(),
			'project_id'                 => $this->plato_project->get_project_id(),
			'latitude'                   => $this->plato_project->get_lat_project(),
			'longitude'                  => $this->plato_project->get_lng_project(),
			'country'                    => $this->country->get_slug(),
			'start_date'                 => $this->plato_project->get_start_date(),
			'min_age'                    => $this->plato_project->get_min_age(),
			'max_age'                    => $this->plato_project->get_max_age(),
			'participation_fee_currency' => $this->plato_project->get_participation_fee_currency(),
			'participation_fee'          => $this->plato_project->get_participation_fee(),
			'_genesis_title'             => $this->get_seo_title(),
			'_genesis_description'       => $this->get_seo_description(),
			'description'                => [
				'description'              => $this->plato_project->get_description(),
				'work'                     => $this->plato_project->get_descr_work(),
				'accomodation_and_food'    => $this->plato_project->get_descr_accomodation_and_food(),
				'location_and_leisure'     => $this->plato_project->get_descr_location_and_leisure(),
				'partner     '             => $this->plato_project->get_descr_partner(),
				'requirements'             => $this->plato_project->get_descr_requirements(),
				'notes'                    => $this->plato_project->get_notes(),
			],
		];

		return $meta_data;
	}

	/**
	 * Bepaalt de status van het project
	 * 
	 * @todo review als eigenschap van type werk
	 */
	protected function get_status() : string {

		if ( $this->is_update ) {
			return $this->product->get_status();
		}

		$status = 'publish';
		foreach ( $this->work_types as $work_type ) {
			if ( 'kinderen' === $work_type->get_slug() ) {
				$status = self::REVIEW_STATUS;
			}
		}
		return $status;
	}

	/**
	 * Zet SEO beschrijving
	 * 
	 * @todo aparte functie Formatting
	 */
	protected function get_seo_description() : string {
		$templates = siw_get_data( 'workcamps/seo-description-templates' );
		$template = implode( SPACE, $templates[ array_rand( $templates, 1 ) ] );
		return $this->parse_description( $template );
	}

	/** Geeft SEO titel terug */
	protected function get_seo_title() : string {
		return sprintf( 'Groepsproject %s - %s', $this->country->get_name(), ucfirst( $this->work_types[0]->get_name() ) );
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
			! $this->product->get_meta( 'use_stockphoto' )
		) {
			$image_id = $product_image->get_project_image(
				$this->plato_project->get_image_file_identifiers(),
				$filename_base,
				$this->plato_project->get_project_id()
			);
			if ( is_int( $image_id ) ) {
				$this->product->update_meta_data( 'has_plato_image', true );
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
	 * - Als dit bij het project is aangegeven
	 * - Als Plato-data veranderd is
	 * - Bij geforceerde volledige update
	 */
	protected function should_be_updated() : bool {

		if ( $this->product->get_meta( 'import_again' ) ) {
			$this->product->update_meta_data( 'import_again', false );
			return true;
		}
		elseif ( $this->plato_project->get_checksum() != $this->product->get_meta( 'checksum' ) ) {
			return true;
		}
		elseif ( siw_get_option( 'plato.force_full_update' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Zet speciale doelgroepen voor projecten
	 * 
	 * @todo extra doelgroepen toevoegen / verplaatsen naar referentiegegevens /refactor
	 */
	protected function set_target_audiences() {

		$target_audiences = [
			'family' => [
				'slug' => 'families',
				'name' => __( 'Families', 'siw' ),
			],
			'teens' => [
				'slug' => 'tieners',
				'name' => __( 'Tieners', 'siw' ),
			],
		];

		$this->target_audiences = [];

		if ( $this->plato_project->get_family() || ( 'FAM' == $this->plato_project->get_project_type() ) ) {
			$this->target_audiences['family'] = $target_audiences['family'];
		}
		if ( $this->plato_project->get_max_age() <= 20 || 'TEEN' == $this->plato_project->get_project_type() ) {
			$this->target_audiences['teens'] = $target_audiences['teens'];
		}
	}

	/** Geeft type groepsproject terug */
	protected function get_workcamp_type() : string {
		if ( array_key_exists( 'family', $this->target_audiences ) ) {
			$workcamp_type = 'familieproject';
		}
		elseif ( array_key_exists( 'teens', $this->target_audiences ) ) {
			$workcamp_type = 'tienerproject';
		}
		else {
			$workcamp_type = 'groepsproject';
		}
		return $workcamp_type;
	}

	/** Zet de tarief die van toepassing zijn voor dit project */
	protected function set_tariffs() {
		$this->tariffs = siw_get_data( 'workcamps/tariffs' );
	}

	/** Geeft aan het het een toegestaan type project is */
	protected function is_allowed_project_type() : bool {
		$allowed_project_types = [
			'STV',
			'TEEN',
			'FAM',
			'LNG',
			'SEN'
		];
		return in_array( $this->plato_project->get_project_type(), $allowed_project_types );
	}
}
