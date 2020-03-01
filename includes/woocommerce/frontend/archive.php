<?php

namespace SIW\WooCommerce\Frontend;

use SIW\Formatting;

/**
 * Aanpassingen aan overzichtspagina van groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 */
class Archive {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_project_code_and_dates'] );
		add_filter( 'the_seo_framework_the_archive_title', [ $self, 'set_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $self, 'set_seo_description' ], 10, 2 );
		
		Archive_Header::init();

		add_filter( 'woocommerce_default_catalog_orderby_options', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_catalog_orderby', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_get_catalog_ordering_args', [ $self, 'process_catalog_ordering_args' ] );
		add_filter( 'woocommerce_shortcode_products_query', [ $self, 'process_shortcode_ordering_args'], 10, 2 );

		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );//TODO: werkt pas na switch van thema
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 ); //TODO: werkt pas na switch van thema
	}

	/**
	 * Toont projectcode en datums
	 */
	public function show_project_code_and_dates() {
		global $product;
		$duration = Formatting::format_date_range( $product->get_attribute('startdatum'), $product->get_attribute('einddatum'), false );
		//TODO: inline styling verplaatsen naar css
		echo '<p>' . esc_html( $duration ) . '</p><hr style="margin:5px;">';
		echo '<p style="margin-bottom:5px;"><small>' . esc_html( $product->get_sku() ) . '</small></p>';
	}

	/**
	 * Past de SEO titel aan
	 *
	 * @param string $title
	 * @param \WP_Term $term
	 * @return string
	 */
	public function set_seo_title( string $title, $term ) {

		if ( ! is_a( $term, '\WP_Term') ) {
			return $title;
		}

		switch ( $term->taxonomy ) {
			case 'pa_land':
			case 'product_cat':
				$title = sprintf( __( 'Groepsprojecten in %s', 'siw' ), $term->name );
				break;
			case 'pa_doelgroep':
				$title = sprintf( __( 'Groepsprojecten voor %s', 'siw' ), $term->name );
				break;
			case 'pa_taal':
				$title = sprintf( __( 'Groepsprojecten met voertaal %s', 'siw' ), $term->name );
				break;
			case 'pa_soort-werk':
				$title = sprintf( __( 'Groepsprojecten met werk gericht op %s', 'siw' ), strtolower( $term->name ) );
				break;
			case 'pa_maand':
				$title = sprintf( __( 'Groepsprojecten in de maand %s', 'siw' ), $term->name );
				break;
		}
		return $title;
	}

	/**
	 * Past SEO-beschrijving aan
	 *
	 * @param string $description
	 * @param \WP_Term $term
	 */
	public function set_seo_description( string $description, $term ) {
		if ( ! is_a( $term, '\WP_Term') ) {
			return $description;
		}
		
		switch ( $term->taxonomy ) {
			case 'pa_land':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen in %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), $term->name ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
			case 'pa_soort-werk':
				$description =
					sprintf( __( 'Wil je graag vrijwilligerswerk doen gericht op %s en doe je dit het liefst samen in een groep met andere internationale vrijwilligers?', 'siw' ), strtolower( $term->name ) ) . SPACE .
					__( 'Neem een dan een kijkje bij onze groepsvrijwilligersprojecten.', 'siw' );
				break;
		}

		return $description;
	}

	/**
	 * Voegt extra sorteeropties toe voor archive
	 * 
	 * - Willekeurig
	 * - Startdatum
	 * - Land
	 * @param array $options
	 * @return array
	 */
	public function add_catalog_orderby_options( $options ) {
		$options['startdate'] = __( 'Startdatum', 'siw' );
		$options['country']   = __( 'Land', 'siw' );
		$options['random']    = __( 'Willekeurig', 'siw' );
	
		return $options;
	}

	/**
	 * Verwerkt extra sorteeropties voor archive
	 *
	 * @param array $args
	 * @return array
	 */
	public function process_catalog_ordering_args( $args ) {
		$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		switch ( $orderby_value ) {
			case 'random':
				$sort_args['orderby']  = 'rand';
				$sort_args['order']    = '';
				$sort_args['meta_key'] = '';
				break;
			case 'startdate':
				$sort_args['orderby']  = 'meta_value';
				$sort_args['order']    = 'asc';
				$sort_args['meta_key'] = 'start_date';
				break;
			case 'country':
				$sort_args['orderby']  = 'meta_value';
				$sort_args['order']    = 'asc';
				$sort_args['meta_key'] = 'land';
				break;
		}
		return $sort_args;
	}

	/**
	 * Verwerkt extra sorteeropties voor shortcode
	 *
	 * @param array $args
	 * @param array $atts
	 * @return array
	 */
	public function process_shortcode_ordering_args( $args, $atts ) {
		if ( 'random' == $atts['orderby'] ) {
			$args['orderby']  = 'rand';
			$args['order']    = '';
			$args['meta_key'] = '';
		}
		return $args;
	}
}
