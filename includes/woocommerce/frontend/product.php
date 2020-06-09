<?php

namespace SIW\WooCommerce\Frontend;

use SIW\WooCommerce\Import\Product as Import_Product;
use SIW\WooCommerce\Frontend\Product_Tabs;

/**
 * Aanpassingen aan Groepsproject
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @uses      siw_get_currency()
 */
class Product {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		Product_Tabs::init();

		add_filter( 'woocommerce_is_purchasable', [ $self, 'set_product_is_purchasable'], 10, 2 );
		add_filter( 'woocommerce_available_variation', [ $self, 'set_variation_description'] );
		add_filter( 'woocommerce_sale_flash', [ $self, 'set_sales_flash_text' ] );
		add_filter( 'woocommerce_display_product_attributes', [ $self, 'display_product_attributes'], 10, 2 );
		add_action( 'woocommerce_after_add_to_cart_form', [ $self, 'show_local_fee'] );
		add_filter( 'woocommerce_out_of_stock_message', [ $self, 'set_out_of_stock_message'] );
		add_filter( 'woocommerce_dropdown_variation_attribute_options_args', [ $self, 'set_variation_dropdown_args'] );

		add_action( 'woocommerce_before_single_product_summary', [ $self, 'show_featured_badge' ], 10 );

		/**
		 * Verwijderen diverse woocommerce-hooks
		 * - "Reset variations"-link
		 * - Prijsrange
		 * - Trailing zeroes
		 * - Redundante headers in tabs
		 * Altijd prijs van variatie tonen
		 */
		add_filter( 'woocommerce_reset_variations_link', '__return_empty_string' );
		add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
		add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );
		add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

		//SEO
		add_filter( 'the_seo_framework_post_meta', [ $self, 'set_seo_noindex' ], 10, 2 );
	}

	/**
	 * Bepaalt of product bestelbaar is
	 *
	 * @param bool $is_purchasable
	 * @param \WC_Product $product
	 * @return bool
	 */
	public function set_product_is_purchasable( bool $is_purchasable, \WC_Product $product )  {
		$is_purchasable = $product->is_visible();
		$status = $product->get_status();

		if ( ! $is_purchasable || Import_Product::REVIEW_STATUS == $status ) {
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
	 * @return string
	 */
	public function set_sales_flash_text() {
		return '<span class="onsale">' . __( 'Korting', 'siw' ) . '</span>';
	}

	/**
	 * Past weergave van de attributes aan
	 *
	 * @param array $attributes
	 * @param \WC_Product $product
	 *
	 * @return array
	 */
	public function display_product_attributes( array $attributes, \WC_Product $product ) : array {
		$order = [
			'projectnaam',
			'projectcode',
			'pa_land',
			'pa_soort-werk',
			'startdatum',
			'einddatum',
			'aantal-vrijwilligers',
			'leeftijd',
			'lokale-bijdrage',
			'pa_taal',
			'pa_doelgroep',
		];

		$callback = function( &$value, $key ) {
			$value = 'attribute_' . $value;
		};
		array_walk( $order, $callback );

		uksort( $attributes, function( $key1, $key2 ) use ( $order ) {
			return ( array_search( $key1, $order ) > array_search( $key2, $order ) );
		} );

		//Local fee verbergen voor nederlandse projecten
		if ( 'nederland' === $product->get_meta( 'country' ) ) {
			unset( $attributes['attribute_lokale-bijdrage']);
		}

		return $attributes;
	}
	
	/**
	 * Past melding bij niet meer beschikbaar project aan
	 *
	 * @return string
	 */
	public function set_out_of_stock_message() {
		return __( 'Dit project is helaas niet meer beschikbaar', 'siw' );
	}

	/**
	 * Toont lokale bijdrage indien van toepassing
	 */
	public function show_local_fee() {
		global $product;

		//Local fee niet tonen voor nederlandse projecten
		if ( 'nederland' === $product->get_meta( 'country' ) ) {
			return;
		}

		$amount = $product->get_meta( 'participation_fee' );
		$currency_code = $product->get_meta( 'participation_fee_currency' );

		if ( empty( $currency_code ) || $amount <= 0 ) {
			return;
		}
		
		$currency = siw_get_currency( $currency_code );
		$symbol = $currency_code;
		if ( is_a( $currency, '\SIW\Data\Currency' ) ) {
			$symbol = $currency->get_symbol();
			if ( 'EUR' != $currency_code ) {
				$amount_in_euro = $currency->convert_to_euro( $amount );
			}
		}
		?>
		<div class="participation-fee">
			<?php printf( esc_html__( 'Let op: naast het inschrijfgeld betaal je ter plekke nog een lokale bijdrage van %s %s.', 'siw' ), $symbol, $amount );?>
			<?php if ( isset( $amount_in_euro ) ):?>
				&nbsp;<?php printf ( esc_html__( '(Ca. &euro; %s)', 'siw' ), $amount_in_euro ); ?>
			<?php endif ?>
		</div>
		<?php
	}

	/**
	 * Undocumented function
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public function set_variation_dropdown_args( array $args ) : array {
		$args['show_option_none'] = __( 'Kies een tarief', 'siw' );
		$args['class'] = 'select-css';
		return $args;
	}

	/**
	 * Zet SEO noindex als project niet zichtbaar is
	 *
	 * @param array $meta
	 * @param int $post_id
	 *
	 * @return array
	 */
	function set_seo_noindex( array $meta, int $post_id ) : array {
		if ( 'product' == get_post_type( $post_id ) ) {
			$product = wc_get_product( $post_id );
			$meta['_genesis_noindex'] = intval( ! $product->is_visible() );
		}
		return $meta;
	}

	/**
	 * Toont badge voor aanbevolen projecten
	 * 
	 * @todo template van maken i.v.m. duplicate code in archive
	 */
	public function show_featured_badge() {
		global $product;
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}

}
