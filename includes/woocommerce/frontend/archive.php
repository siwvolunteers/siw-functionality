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

		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_dates'] );
		add_action( 'woocommerce_after_shop_loop_item', [ $self, 'show_project_code'], 1 );

		add_filter( 'the_seo_framework_the_archive_title', [ $self, 'set_seo_title'], 10, 2 );
		add_filter( 'the_seo_framework_generated_archive_excerpt', [ $self, 'set_seo_description' ], 10, 2 );
		
		Archive_Header::init();

		add_filter( 'woocommerce_default_catalog_orderby_options', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_catalog_orderby', [ $self, 'add_catalog_orderby_options' ] );
		add_filter( 'woocommerce_get_catalog_ordering_args', [ $self, 'process_catalog_ordering_args' ], 10, 3 );
	}

	/**
	 * Toont datums
	 */
	public function show_dates() {
		global $product;
		$duration = Formatting::format_date_range( $product->get_attribute('startdatum'), $product->get_attribute('einddatum'), false );
		echo wpautop( esc_html( $duration ) );
	}

	/**
	 * Toont projectcode
	 */
	public function show_project_code() {
		global $product;
		echo '<hr>';
		echo '<span class="project-code">' . esc_html( $product->get_sku() ) . '</span>';
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
	 * Voegt extra sorteeroptie (startdatum) toe voor archive
	 * 
	 * @param array $options
	 * 
	 * @return array
	 */
	public function add_catalog_orderby_options( $options ) {
		unset( $options['menu_order'] );
		unset( $options['popularity'] );
		unset( $options['rating'] );
		unset( $options['date'] );
		unset( $options['price'] );
		unset( $options['price-desc'] );
		$options['startdate'] = __( 'Startdatum', 'siw' );
		return $options;
	}

	/**
	 * Verwerkt extra sorteeroptie voor archive
	 *
	 * @param array $args
	 * @return array
	 */
	public function process_catalog_ordering_args( $args, $orderby, $order ) {
		if ( 'startdate' == $orderby ) {
			$args['orderby']  = 'meta_value';
			$args['meta_key'] = 'start_date';
		}
		return $args;
	}
}
