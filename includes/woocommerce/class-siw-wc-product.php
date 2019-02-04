<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen aan Groepsproject
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      siw_get_currency()
 */

class SIW_WC_Product {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_product_tabs', [ $self, 'remove_reviews_tab'], PHP_INT_MAX );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_project_location_map_tab'] );
		add_filter( 'woocommerce_product_tabs', [ $self, 'add_contact_form_tab'] );
		add_filter( 'woocommerce_is_purchasable', [ $self, 'set_product_is_purchasable'], 10, 2 );
		add_filter( 'woocommerce_available_variation', [ $self, 'set_variation_description'] );
		add_filter( 'woocommerce_sale_flash', [ $self, 'set_sales_flash_text' ] );
		add_filter( 'woocommerce_product_get_attributes', [ $self, 'order_product_attributes'] );
		add_filter( 'woocommerce_related_products_args', [ $self, 'set_related_products_number'], PHP_INT_MAX );
		add_action( 'woocommerce_after_add_to_cart_form', [ $self, 'show_local_fee'] );

		/*
		 * Verwijderen diverse woocommerce-hooks
		 * - "Reset variations"-link
		 * - Prijsrange
		 * - Trailing zeroes
		 * - Redundante headers in tabs
		 * - Meta-informatie (tags, categorie, SKU)
		 * Altijd prijs van variatie tonen
		 */
		add_filter( 'woocommerce_reset_variations_link', '__return_false' );
		add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
		add_filter( 'woocommerce_product_description_heading', '__return_false' );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_false' );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		add_filter( 'woocommerce_show_variation_price', '__return_true' );
	}

	/**
	 * Verwijdert reviews-tab
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function remove_reviews_tab( $tabs ) {
		unset( $tabs['reviews'] );
		return $tabs;	
	}

	/**
	 * Voegt tab met projectlocatie toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_project_location_map_tab( $tabs ) {
		global $product;
		$latitude = $product->get_meta( 'latitude' );
		$longitude = $product->get_meta( 'longitude' );
	
		if ( 0 != $latitude && 0 != $longitude ) {
			$tabs['location'] = [
				'title'     => __( 'Projectlocatie', 'siw' ),
				'priority'  => 110,
				'callback'  => [ $this, 'show_project_map'],
				'latitude'  => $latitude,
				'longitude' => $longitude,
			];
		}
		return $tabs;	
	}

	/**
	 * Voegt tab met contactformulier toe
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_contact_form_tab( $tabs ) {
		$tabs['enquiry'] = [
			'title'    => __( 'Stel een vraag', 'siw' ),
			'priority' => 120,
			'callback' => [ $this, 'show_product_contact_form' ],
		];
		return $tabs;	
	}

	/**
	 * Toont kaart met projectlocatie in tab
	 * @param  array $tab
	 * @param  array $args
	 */
	public function show_project_map( $tab, $args ) {
		echo do_shortcode( sprintf( '[gmap address="%s,%s" title="%s" zoom="6" maptype="ROADMAP"]', esc_attr( $args['latitude'] ), esc_attr( $args['longitude'] ), esc_attr__( 'Projectlocatie', 'siw' ) ) );
	}

	/**
	 * Toont contactformulier in tab
	 */
	public function show_product_contact_form() {
		echo do_shortcode( '[caldera_form id="contact_project"]' );
	}

	/**
	 * Bepaalt of product bestelbaar is
	 *
	 * @param bool $is_purchasable
	 * @param WC_Product $product
	 * @return bool
	 */
	public function set_product_is_purchasable( $is_purchasable, $product ) {
		$is_purchasable = $product->is_visible();

		if ( false == $is_purchasable ) {
			
			remove_action( 'woocommerce_single_variation', 'kt_woocommerce_single_variation', 10 ); //TODO: kan weg na switch theme
			remove_action( 'woocommerce_single_variation', 'kt_woocommerce_single_variation_add_to_cart_button', 20 );
			
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			add_filter( 'woocommerce_variation_is_visible', '__return_false');
		}
		return $is_purchasable;
	}

	/**
	 * Zet de toelichting voor het studententarief
	 *
	 * @param array $variations
	 * @return array
	 */
	public function set_variation_description( $variations ) {
		if ( 'student' == $variations['attributes']['attribute_pa_tarief'] ) {
			$variations['variation_description'] =  __( 'Je komt in aanmerking voor het studententarief als je 17 jaar of jonger bent of als je een bewijs van inschrijving kunt laten zien.', 'siw' );
		}
		return $variations;
	}

	/**
	 * Zet de aanbiedingstekst
	 *
	 * @param string $text
	 * @return string
	 */
	public function set_sales_flash_text( $text ) {
		return '<span class="onsale">' . __( 'Korting', 'siw' ) . '</span>';
	}

	/**
	 * Sorteert de attributes
	 *
	 * @param array $attributes
	 * @return array
	 */
	public function order_product_attributes( $attributes ) {
		$order = array(
			'pa_projectnaam', //TODO: verwijderen indien mogelijk
			'projectnaam',
			'pa_projectcode', //TODO: verwijderen indien mogelijk
			'projectcode',
			'pa_land',
			'pa_soort-werk',
			'pa_startdatum', //TODO: verwijderen indien mogelijk
			'startdatum',
			'pa_einddatum', //TODO: verwijderen indien mogelijk
			'einddatum',
			'pa_aantal-vrijwilligers', //TODO: verwijderen indien mogelijk
			'aantal-vrijwilligers',
			'pa_leeftijd', //TODO: verwijderen indien mogelijk
			'leeftijd',
			'pa_lokale-bijdrage', //TODO: verwijderen indien mogelijk
			'lokale-bijdrage',
			'pa_taal',
			'pa_vog',
			'pa_doelgroep',
		);
		uksort( $attributes, function( $key1, $key2 ) use ( $order ) {
			return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
		} );
		return $attributes;
	}
	
	/**
	 * Zet het aantal related products op 4
	 *
	 * @param array $args
	 * @return array
	 */
	public function set_related_products_number( $args ) {
		$args['posts_per_page'] = 4;
		return $args;
	}

	/**
	 * Toont lokale bijdrage indien van toepassing
	 */
	public function show_local_fee() {
		global $product;
		$participation_fee = $product->get_meta( 'participation_fee' );
		$participation_fee_currency = $product->get_meta( 'participation_fee_currency' );

		if ( ! empty( $participation_fee_currency ) && $participation_fee > 0 ) {
			$currency = siw_get_currency( $participation_fee_currency );
			$symbol = $participation_fee_currency;
			if ( false != $currency && 'EUR' != $participation_fee_currency ) {
				$symbol = $currency->get_symbol();
				$amount_in_euro = $currency->convert_to_euro( $participation_fee );
	
			}
			?>
			<div class="local-fee">
				<?php printf( esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s %s.', 'siw' ), $symbol, $participation_fee );?>
				<?php if ( isset( $amount_in_euro ) ):?>
					&nbsp;<?php printf ( esc_html__( '(Ca. &euro; %s)', 'siw' ), $amount_in_euro ); ?>
				<?php endif ?>
			</div>
			<?php
		}
	}
}