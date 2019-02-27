<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Import van een Groepsproject
 *
 * @author      Maarten Bruna
 * @package     SIW\Workcamps\Import
 * @copyright   2018-2019 SIW Internationale Vrijwilligersprojecten
 * @uses        SIW_Country
 * @uses        SIW_Language
 * @uses        SIW_Work_Type
 * @uses        SIW_Formatting
 */
class SIW_WC_Import_Product {

	/**
	 * Post status van projecten die beoordeel moeten worde3n
	 */
	const REVIEW_STATUS = 'pending';

	/**
	 * Ruwe xml-data uit Plato
	 *
	 * @var stdClass
	 */
	protected $xml;

	/**
	 * Geeft aan of het een update van een bestaand product is
	 *
	 * @var bool
	 */
	protected $update = false;

	/**
	 * Project
	 *
	 * @var WC_Product
	 */
	protected $product;

	/**
	 * Land van project
	 *
	 * @var SIW_Country
	 */
	protected $country;

	/**
	 * Projecttalen
	 *
	 * @var SIW_Language[]
	 */
	protected $languages;

	/**
	 * Soort werk van het project
	 *
	 * @var SIW_Work_Type[]
	 */
	protected $work_types;

	/**
	 * Tarieven die van toepassing zijn
	 *
	 * @var array
	 */
	protected $tariffs;

	/**
	 * Doelgroepen
	 *
	 * @var array
	 */
	protected $target_audiences = [];

	/**
	 * Constructor
	 */
	public function __construct( $data ) {
		add_filter( 'wc_product_has_unique_sku', '__return_false' );
		add_filter( 'wp_insert_post_data', [ $this, 'correct_post_slug'], 10, 2 );
		$this->xml = (object) $data;
		$this->set_country();
		$this->set_languages();
		$this->set_work_types();
	}

	/**
	 * Corrigeert slug van product als het ter review staat
	 *
	 * @param array $data
	 * @param array $postarr
	 * @return array
	 */
	public function correct_post_slug( $data, $postarr ) {

		if ( self::REVIEW_STATUS == $data['post_status'] && 'product' == $data['post_type'] ) {
			$data['post_name'] =  $postarr['post_name'];
		}

		return $data;
	}


	/**
	 * Verwerk item
	 * 
	 * @return bool
	 * 
	 * @todo logging als land/werk/code leeg is
	 */
	public function process() {

		if ( false == $this->country || empty( $this->work_types ) || empty( $this->xml->code ) ) {
			return false;
		}

		/* Voorbereiden */
		$this->set_target_audiences();
		$this->set_tariffs();

		/* Zoek project */
		$args = [
			'project_id' => $this->xml->project_id,
			'return'     => 'objects',
			'limit'      => -1,
		];
		$products = wc_get_products( $args );

		if ( ! empty( $products ) ) {
			$this->update = true;
			$this->product = $products[0];
			if ( true !== $this->should_be_updated() ) {
				//$this->set_variations();
				return false;
			}
			
		}
		else {
			if ( ! $this->is_allowed_project_type() || false == $this->country->is_allowed() || date( 'Y-m-d' ) > $this->xml->start_date ) {
				return false;
				//TODO check op startdatum in het verleden
			}
			$this->product = new WC_Product_Variable;
		}

		$this->set_product();
		$this->set_variations();
		return true;
	}

	/**
	 * Zet de eigenschappen van het product
	 */
	public function set_product() {
		$this->product->set_props( [
			'name'               => $this->get_name(),
			'slug'               => $this->get_slug(),
			'description'        => $this->get_description(),
			'short_description'  => $this->get_short_description(),
			'category_ids'       => $this->get_category_ids(),
			'attributes'         => $this->get_attributes(),
			'default_attributes' => $this->get_default_attributes(),
			'tag_ids'            => $this->get_tag_ids(),
			'sku'                => $this->xml->code,
			'sold_individually'  => true,
			'virtual'            => true,
			'status'             => $this->get_status(),
			//'image_id'           => $this->get_image_id(),

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
		$country = strtoupper( $this->xml->country );
		$this->country = siw_get_country( $country, 'iso' );
	}

	/**
	 * Zet talen op basis van Plato-code
	 */
	protected function set_languages() {
		$this->languages = [];
		$languages = explode( ',', $this->xml->languages );
		foreach ( $languages as $language_code ) {
			$language_code = strtoupper( $language_code );
			$language = siw_get_language( $language_code, 'plato' );
			if ( false != $language ) {
				$this->languages[] = $language;
			}
		}
	}

	/**
	 * Zet soorten werk op basis van Plato-code
	 */
	protected function set_work_types() {
		$this->work_types = [];
		$work_types = explode( ',', $this->xml->work );
		$work_types = array_unique( $work_types );

		foreach ( $work_types as $work_type_code ) {
			$work_type_code = strtoupper( $work_type_code );
			$work_type = siw_get_work_type( $work_type_code, 'plato' );
			if ( false != $work_type ) {
				$this->work_types[] = $work_type;
			}
		}
	}

	/**
	 * Geeft de category (continent) van het project terug
	 * 
	 * @return array
	 */
	protected function get_category_ids() {
		$continent = $this->country->get_continent();
		$category_ids = [];
		if ( $category_id = $this->maybe_create_term( 'product_cat', $continent->get_slug(), $continent->get_name() ) ) {
			$category_ids[] = $category_id;
		}
		return $category_ids;
	}

	/**
	 * Geeft naam van het project terug
	 * 
	 * @return string
	 */
	protected function get_name() {
		$country = $this->country->get_name();
		$work_types = array_slice( $this->work_types, 0, 2 );
		if ( 1 == count( $work_types ) ) {
			$work = $work_types[0]->get_name();
		}
		else {
			$work = sprintf( '%s en %s', $work_types[0]->get_name(), strtolower( $work_types[1]->get_name() ) );
		}
		$name = sprintf( '%s | %s', $country, ucfirst( $work ) );
		return $name;
	}

	/**
	 * Zet de url-slug van het project
	 * 
	 * Formaat: jaar-projectcode-projectnaam
	 * @return string
	 */
	protected function get_slug() {
		$year = date( 'Y', strtotime( $this->xml->start_date ) );
		$code = $this->xml->code;
		$name = $this->get_name();
		$slug = sanitize_title( sprintf( '%s-%s-%s', $year, $code, $name ) );
		return $slug;
	}

	/**
	 * Creert term indien deze nog niet bestaat
	 *
	 * @param string $taxonomy
	 * @param string $slug
	 * @param string $name
	 * @return int
	 */
	protected function maybe_create_term( $taxonomy, $slug, $name ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( false == $term ) {
			$new_term = wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );
			return $new_term['term_id'] ?? false;
		}
		else {
			return $term->term_id;
		}
		return false;
	}

	/**
	 * Geeft tag-ids van het project terug
	 *
	 * - Land
	 * - Soort werk
	 * - Doelgroep
	 * 
	 * @return array
	 */
	protected function get_tag_ids() {
		$tags[ $this->country->get_slug() ] = $this->country->get_name();
		foreach ( $this->work_types as $work_type ) {
			$tags[ $work_type->get_slug() ] = $work_type->get_name();
		}
		foreach ( $this->target_audiences as $target_audience ) {
			$tags[ $target_audience['slug'] ] = $target_audience['name'];
		}

		$tag_ids = [];
		foreach ( $tags as $slug => $name ) {
			if ( $tag_id = $this->maybe_create_term( 'product_tag', $slug, $name ) ) {
				$tag_ids[] = $tag_id;
			}
		}
		return $tag_ids;
	}

	/**
	 * Zet de eigenschappen van het project
	 *
	 * @return array
	 */
	protected function get_attributes() {

		$attributes = [];

		/* Product attributes */
		$product_attributes = [
			'Projectnaam'          => $this->xml->name,
			'Projectcode'          => $this->xml->code,
			'Startdatum'           => date( 'j-n-Y', strtotime( $this->xml->start_date ) ),
			'Einddatum'            => date( 'j-n-Y', strtotime( $this->xml->end_date ) ),
			'Aantal vrijwilligers' => $this->format_number_of_volunteers(),
			'Leeftijd'             => $this->format_age_range(),
			'Lokale bijdrage'      => $this->format_local_fee(),
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
		$month_name = ucfirst( SIW_Formatting::format_month( $this->xml->start_date, true ) );
		$month_slug = sanitize_title( $month_name );
		$taxonomy_attributes['maand']['visible'] = false;
		$taxonomy_attributes['maand']['values'][] = [
			'slug'  => $month_slug,
			'name'  => $month_name,
			'order' => date( 'Ym', strtotime( $this->xml->start_date ) ),
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

		foreach ( $taxonomy_attributes as $taxonomy => $attribute ) {
			$attribute = wp_parse_args(
				$attribute,
				[
					'visible'   => true,
					'variation' => false,
					'values'    => []
				]
			);

			if ( ! empty( $attribute['values'] ) ) {
				$attributes["pa_{$taxonomy}"] = $this->create_taxonomy_attribute( $taxonomy, $attribute['values'], $attribute['visible'], $attribute['variation']  );
			}
		}

		return $attributes;
	}

	/**
	 * Geeft default eigenschappen terug
	 * 
	 * @return array
	 */
	protected function get_default_attributes() {
		$max_age = (int) $this->xml->max_age;
		$default_tariff = ( 18 > $max_age ) ? 'student' : 'regulier';
		$default_attributes = [ 'pa_tarief' => $default_tariff ];
		return $default_attributes;
	}

	/**
	 * Creëert product attribute
	 *
	 * @param string $name
	 * @param array $options
	 * @param bool $visible
	 * @param boolean $taxonomy
	 * @return WC_Product_Attribute
	 */
	protected function create_product_attribute( $name, $options, $visible = true ) {
		$options = (array) $options;
		$attribute = new WC_Product_Attribute;
		$attribute->set_name( $name );
		$attribute->set_visible( $visible );
		$attribute->set_options( $options );
		return $attribute;
	}

	/**
	 * Creëert taxonomy attribute
	 *
	 * @param string $taxonomy
	 * @param array $values
	 * @param bool $visible
	 * @param bool $variation
	 * @return array
	 * 
	 * @todo maybe_create_taxonomy of logging als taxonomy niet bestaat
	 */
	protected function create_taxonomy_attribute( $taxonomy, $values, $visible = true, $variation = false ) {

		$wc_attribute_taxonomy_id = wc_attribute_taxonomy_id_by_name( $taxonomy );
		if ( false == $wc_attribute_taxonomy_id ) {
			return false;
		}

		foreach ( $values as $value ) {
			$options[] = $this->maybe_create_term( "pa_{$taxonomy}", $value['slug'], $value['name'] );
		}

		$attribute = new WC_Product_Attribute;
		$attribute->set_id( $wc_attribute_taxonomy_id );
		$attribute->set_options( $options );
		$attribute->set_name( "pa_{$taxonomy}" );
		$attribute->set_visible( $visible );
		$attribute->set_variation( $variation );

		return $attribute;
	}

	/**
	 * Genereert de beschrijving
	 * 
	 * @return string
	 */
	protected function get_description() {

		$panes = [
			[
				'title'   => __( 'Beschrijving', 'siw' ),
				'content' => $this->xml->description,
			],
			[
				'title'   => __( 'Werk', 'siw' ),
				'content' => $this->xml->descr_work,
			],
			[
				'title'   => __( 'Accommodatie en maaltijden', 'siw' ),
				'content' => $this->xml->descr_accomodation_and_food,
			],
			[
				'title'   => __( 'Locatie en vrije tijd', 'siw' ),
				'content' => $this->xml->descr_location_and_leisure,
			],
			[
				'title'   => __( 'Organisatie', 'siw' ),
				'content' => $this->xml->descr_partner,
			],
			[
				'title'   => __( 'Vereisten', 'siw' ),
				'content' => $this->xml->descr_requirements,
			],
			[
				'title'   => __( 'Opmerkingen', 'siw' ),
				'content' => $this->xml->notes,
			],
		];
		$description = SIW_Formatting::generate_accordion( $panes );
		return $description;
	}

	/**
	 * Parset beschrijvingen
	 *
	 * @param string $template
	 * @return string
	 */
	protected function parse_description( $template ) {
		$vars = [
			'project_type' => $this->get_workcamp_type(),
			'country'      => $this->country->get_name(),
			'dates'        => SIW_Formatting::format_date_range( $this->xml->start_date, $this->xml->end_date, false),
			'participants' => (int) $this->xml->numvol,
			'ages'         => $this->format_age_range(),
			'work_type'    => strtolower( $this->work_types[0]->get_name() ),
		];
		$description = SIW_Formatting::parse_template( $template, $vars );
		return $description;
	}


	/**
	 * Geneert de korte (Nederlandse) beschrijving van een project op basis van een template
	 * 
	 * @return string
	 */
	protected function get_short_description() {
		$templates = [];
		/**
		 * Array met templates voor beschrijvingen van groepsprojecten
		 *
		 * @param array $templates
		 */
		$templates = apply_filters( 'siw_workcamp_description_templates', $templates );
	
		$template = implode( $templates[ array_rand( $templates, 1 ) ], SPACE ); //TODO: functie in SIW_Formatting
		$short_description = $this->parse_description( $template );

		return $short_description;
	}

	/**
	 * Zet meta properties van product
	 * 
	 * @return array
	 */
	protected function get_meta_data() {
		$meta_data = [
			'project_id'                 => $this->xml->project_id,
			'latitude'                   => $this->xml->lat_project,
			'longitude'                  => $this->xml->lng_project,
			'country'                    => $this->country->get_slug(),
			'start_date'                 => $this->xml->start_date,//TODO: is nu startdatum dus dat moet overal aangepast worden
			'min_age'                    => $this->xml->min_age,
			'max_age'                    => $this->xml->max_age, 
			'participation_fee_currency' => $this->xml->participation_fee_currency,
			'participation_fee'          => $this->xml->participation_fee,
			'xml'                        => (array) $this->xml,
			'_genesis_title'             => $this->get_seo_title(),
			'_genesis_description'       => $this->get_seo_description(),
		];

		return $meta_data;
	}

	/**
	 * Zet de benodige variaties (tarieven)
	 */
	protected function set_variations() {
		$tariffs = $this->tariffs;

		/* Controleer variaties bij bestaande projecten */
		if ( true == $this->update ) {
			$variations = $this->product->get_children();
			foreach ( $variations as $variation_id ) {
				$variation = wc_get_product( $variation_id );
				if ( false === $variation ) {
					continue;
				}
				$variation_tariff = $variation->get_attributes()['pa_tarief'];
				if ( isset( $tariffs[ $variation_tariff ] ) ) {
					unset( $tariffs[ $variation_tariff ] );
				}
				else {
					$variation->delete( true );
				}
			}
		}

		$sale = SIW_Util::is_workcamp_sale_active();

		/* Maak nieuwe variaties aan indien nodig */
		foreach ( $tariffs as $slug => $tariff ) {
			$variation = new WC_Product_Variation;
			$variation->set_props( [
				'parent_id'         => $this->product->get_id(),
				'attributes'        => [ 'pa_tarief' => $slug ],
				'virtual'           => true,
				'regular_price'     => $tariff['regular_price'],
				'sale_price'        => $sale ? $tariff['sale_price'] : null,
				'price'             => $sale ? $tariff['sale_price'] : $tariff['regular_price'],
				'date_on_sale_from' => $sale ? date( DATE_ISO8601, strtotime( siw_get_setting( 'workcamp_sale_start_date' ) ) ) : null,
				'date_on_sale_to'   => $sale ? date( DATE_ISO8601, strtotime( siw_get_setting( 'workcamp_sale_end_date' ) ) ) : null,
			]);
			$variation->save();
		}
	}

	/**
	 * Bepaalt de status van het project
	 * 
	 * @return string
	 * 
	 * @todo 
	 */
	protected function get_status() {

		if ( true == $this->update ) {
			return $this->product->get_status();
		}

		$status = 'publish';
		foreach ( $this->work_types as $work_type ) {
			if ( 'kinderen' == $work_type->get_slug() ) {
				$status = self::REVIEW_STATUS;
			}
		}
		return $status;
	}

	/**
	 * Zet SEO beschrijving
	 * 
	 * @todo splitsen
	 */
	protected function get_seo_description() {

		$templates = [];
		/**
		 * Array met templates voor SEO-beschrijvingen van groepsprojecten
		 *
		 * @param array $templates
		 */
		$templates = apply_filters( 'siw_workcamp_seo_description_templates', $templates );
		$template = implode( $templates[ array_rand( $templates, 1 ) ], SPACE ); //TODO: functie in SIW_Formatting

		$seo_description = $this->parse_description( $template );
		return $seo_description;
	}

	/**
	 * Geeft SEO titel terug
	 * 
	 * @return string
	 */
	protected function get_seo_title() {
		$seo_title = sprintf( 'Groepsproject %s - %s', $this->country->get_name(), ucfirst( $this->work_types[0]->get_name() ) );
		return $seo_title;
	}

	/**
	 * Geeft id van featured afbeelding terug
	 * 
	 * @return int
	 * 
	 * @todo aparte class voor maken
	 */
	protected function get_image_id() {
		
		return 0;
	}

	/**
	 * Geeft aan of project bijgewerkt moet worden
	 *
	 * @return bool
	 */
	protected function should_be_updated() {
		$should_be_updated = ( (array) $this->xml ) == $this->product->get_meta('xml') ? false : true;
		return $should_be_updated;
	}

	/**
	 * Formatteert lokale bijdrage
	 * 
	 * @return string
	 */
	protected function format_local_fee() {
		$fee = (int) $this->xml->participation_fee;
		$currency_code = $this->xml->participation_fee_currency;

		if ( 0 == $fee || ! is_string( $currency_code ) ) {
			return '';
		}
		$currency = siw_get_currency( $currency_code );
		if ( $currency && 'EUR' != $currency_code ) {
			$local_fee = sprintf( '%s %d (%s)', $currency->get_symbol(), $fee, $currency->get_name() );
		}
		elseif ( 'EUR' == $currency_code ) {
			$local_fee = sprintf( '&euro; %s', $fee );
		}
		else {
			$local_fee = sprintf( '%s %d', $currency_code, $fee );
		}
		return $local_fee;
	}

	/**
	 * Formatteert aantal vrijwilligers
	 *
	 * @return string
	 */
	protected function format_number_of_volunteers() {
		$numvol_m = (integer) $this->xml->numvol_m;
		$numvol_f = (integer) $this->xml->numvol_f;
		$numvol = (integer) $this->xml->numvol;
	
		$male_label = ( 1 == $numvol_m ) ? 'man' : 'mannen';
		$female_label = ( 1 == $numvol_f ) ? 'vrouw' : 'vrouwen';
	
		if ( $numvol == ( $numvol_m + $numvol_f ) ) {
			$number_of_volunteers = sprintf( '%d (%d %s en %d %s)', $numvol, $numvol_m, $male_label, $numvol_f, $female_label );
		}
		else {
			$number_of_volunteers = $numvol;
		}
		return $number_of_volunteers;
	}

	/**
	 * Formatteert leeftijden
	 * 
	 * @return string
	 */
	protected function format_age_range() {
		$min_age = (int) $this->xml->min_age;
		$max_age = (int) $this->xml->max_age;
		if ( $min_age < 1 ) {
			$min_age = 18;
		}
		if ( $max_age < 1 ) {
			$max_age = 99;
		}
		$age_range = sprintf( '%d t/m %d jaar', $min_age, $max_age );
		return $age_range;
	}

	/**
	 * Zet speciale doelgroepen voor projecten
	 * 
	 * @todo extra doelgroepen toevoegen / verplaatsen naar referentiegegevens
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

		$project_type = $this->xml->project_type;
		$min_age = (int) $this->xml->min_age;
		$max_age = (int) $this->xml->max_age;
		$family = (bool) $this->xml->family;

		if ( ( true == $family ) || ( 'FAM' == $project_type ) ) {
			$this->target_audiences['family'] = $target_audiences['family'];
		}
		if ( ( $min_age < 17 && $min_age > 12 && $max_age < 20 ) || ( 'TEEN' == $project_type ) ) {
			$this->target_audiences['teens'] = $target_audiences['teens'];
		}
	}

	/**
	 * Geeft type groepsproject terug
	 * 
	 * @return string
	 */
	protected function get_workcamp_type() {
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

	/**
	 * Zet de tarief die van toepassing zijn voor dit project
	 * 
	 * @todo tarieven verplaatsen naar referentiegegevens / properties
	 */
	protected function set_tariffs() {
		$this->tariffs = [
			'regulier' => [
				'name'          => 'regulier',
				'regular_price' => SIW_Properties::WORKCAMP_FEE_REGULAR,
				'sale_price'    => SIW_Properties::WORKCAMP_FEE_REGULAR_SALE
			],
			'student' => [
				'name'          => 'student / <18',
				'regular_price' => SIW_Properties::WORKCAMP_FEE_STUDENT,
				'sale_price'    => SIW_Properties::WORKCAMP_FEE_STUDENT_SALE
			]
		];
	}

	/**
	 * Geeft aan het het een toegestaan type project is
	 *
	 * @return bool
	 */
	protected function is_allowed_project_type() {
		$allowed_project_types = [
			'STV',
			'TEEN',
			'FAM',
		];
		return in_array( $this->xml->project_type, $allowed_project_types );
	}
}