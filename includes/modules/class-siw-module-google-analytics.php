<?php

/**
 * Google Analytics integratie
 * 
 * @package   SIW\Modules
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Enhanced_Ecommerce
 */
class SIW_Module_Google_Analytics {

	/**
	 * Key voor WooCommerce-sessie
	 */
	const SESSION_SCRIPT_KEY = 'siw_enhanced_ecommerce_script';

	/**
	 * Google Analytics property ID
	 *
	 * @var string
	 */
	protected $property_id;

	/**
	 * Geeft aan of de scripts in de footer geplaatst moeten worden
	 *
	 * @var boolean
	 */
	protected $in_footer = true;

	/**
	 * Instellingen voor tracker
	 *
	 * @var array
	 */
	protected $tracker_settings = [
		'anonymizeIp' => true,
		'forceSSL'    => true,
	];

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		$self->set_property_id();

		if ( false == $self->tracking_enabled() ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ] );
		add_action( 'woocommerce_add_to_cart', [ $self, 'track_add_to_cart'], 10, 6 );
		add_filter( 'woocommerce_cart_item_remove_link', [ $self, 'add_variation_id_to_cart_item_remove_link' ], 10 ,2 );
	}

	/**
	 * Haalt het GA property ID op
	 */
	protected function set_property_id() {
		$this->property_id = siw_get_option( 'google_analytics_property_id' );
	}

	/**
	 * Geeft aan of tracking ingeschakeld moet worden
	 *
	 * @return bool
	 */
	protected function tracking_enabled() {
		if ( ! isset( $this->property_id ) || is_user_logged_in() ) {
			return false;
		}
		return true;
	}

	/**
	 * Voegt scripts toe
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'google-analytics', 'https://www.google-analytics.com/analytics.js', null, null, $this->in_footer );
		wp_register_script( 'siw-analytics', SIW_ASSETS_URL . 'js/siw-analytics.js', [ 'google-analytics', 'jquery' ], SIW_PLUGIN_VERSION, $this->in_footer );
		wp_localize_script( 'siw-analytics', 'siw_analytics_cart', $this->generate_cart_data() );
		wp_enqueue_script( 'siw-analytics' );
		wp_add_inline_script( 'google-analytics', $this->generate_snippet(), 'after' );
	}

	/**
	 * Genereert snippet
	 * 
	 * @return string
	 */
	protected function generate_snippet() {
		$snippet = [
			"window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;",
			sprintf( "ga('create','%s',{'siteSpeedSampleRate': 100});", esc_js( $this->property_id ) ),
		];
		foreach ( $this->tracker_settings as $setting => $value ) {
			$snippet[] = sprintf( "ga('set', '%s', %s);", esc_js( $setting ), esc_js( $value ) );
		}

		$snippet = array_merge( $snippet, $this->generate_ecommerce_script() );
		$snippet[] = "ga('send','pageview');";
		$snippet = array_merge( $snippet, $this->get_script_from_session() );
		
		return implode( PHP_EOL, $snippet );
	}
	
	/**
	 * Genereert Enhanced Ecommerce script
	 * 
	 * @return array
	 */
	protected function generate_ecommerce_script() {
		if ( is_product() ) {
			$product = wc_get_product( get_the_ID() );
			$product_data = $this->get_product_data( $product );
			$ecommerce_script = [
				"ga('require', 'ec');",
				sprintf( "ga('ec:addProduct', %s);", json_encode( $product_data ) ),
				"ga('ec:setAction', 'detail');",
			];
		}
		elseif ( is_checkout() && ! is_order_received_page() ) {
			$ecommerce_script = ["ga('require', 'ec');"];
			
			$items = WC()->cart->get_cart_contents();

			foreach ( $items as $item ) {
				$product = wc_get_product( $item['product_id'] );
				$variation = wc_get_product( $item['variation_id'] );
				$product_data = $this->get_product_data( $product, $variation );
				$ecommerce_script[] = sprintf( "ga('ec:addProduct', %s);", json_encode( $product_data ) );
			}
			$ecommerce_script[] = "ga('ec:setAction','checkout')";
		}
		elseif ( is_order_received_page() ) {
			$order_id = get_query_var('order-received');
			$order = wc_get_order( $order_id );

			$ecommerce_script = ["ga('require', 'ec');"];

			$items = $order->get_items();
			foreach ( $items as $item ) {
				$product = wc_get_product( $item['product_id'] );
				$variation = wc_get_product( $item['variation_id'] );
				$product_data = $this->get_product_data( $product, $variation );
				$ecommerce_script[] = sprintf( "ga('ec:addProduct', %s);", json_encode( $product_data ) );
			}

			$order_data = [ 
				'id'     => $order->get_id(),
				'revenue' => number_format( $order->get_total(), 2 ),
				'coupon'  => implode( ',', $order->get_used_coupons()),
			];
			$ecommerce_script[] = sprintf( "ga('ec:setAction', 'purchase', %s);", json_encode( $order_data ) );
		}
		else {
			return [];
		}
		return $ecommerce_script;
	}

	/**
	 * Geeft productdata voor GA terug
	 *
	 * @param WC_Product $product
	 * @param WC_Product_Variation $variation
	 * 
	 * @return array
	 * 
	 * @todo land of partner als brand toevoegen
	 */
	protected function get_product_data( WC_Product $product, WC_Product_Variation $variation = null ) {

		$category_ids = $product->get_category_ids();
		$category = get_term( $category_ids[0], 'product_cat' );

		$product_data = [
			'id'       => esc_js( $product->get_sku() ),
			'name'     => esc_js( $product->get_title() ),
			'category' => esc_js( $category->name ),
		];
		if ( null != $variation ) {
			$product_data = array_merge(
				$product_data,
				[
					'variant'  => $variation->get_variation_attributes()['attribute_pa_tarief'],
					'price'    => number_format( $variation->get_price(), 2 ),
					'quantity' => 1,
				]
			);
		}

		return $product_data;
	}

	/**
	 * Genereert cart data (om verwijderen uit cart te tracken)
	 * 
	 * @return array
	 */
	protected function generate_cart_data() {
		$items = WC()->cart->get_cart_contents();
		$cart_data = [];
		foreach ( $items as $key => $item ) {
			$product = wc_get_product( $item['product_id'] );
			$variation = wc_get_product( $item['variation_id'] );
			$cart_data[ $item['variation_id'] ] = $this->get_product_data( $product, $variation );
		}
		return $cart_data;
	}

	/**
	 * Track toevoegen aan cart
	 *
	 * @param string $cart_item_key
	 * @param int $product_id
	 * @param int $quantity
	 * @param int $variation_id
	 * @param array $variation
	 * @param array $cart_item_data
	 */
	public function track_add_to_cart( string $cart_item_key, int $product_id, int $quantity, int $variation_id, array $variation, array $cart_item_data ) {
		$product = wc_get_product( $product_id );
		$variation = wc_get_product( $variation_id );

		$product_data = $this->get_product_data( $product, $variation );
		
		$script = [
			"ga('require', 'ec');",
			sprintf( "ga('ec:addProduct', %s);", json_encode( $product_data ) ),
			"ga('ec:setAction', 'add');",
			"ga('send', 'event', 'Ecommerce', 'add', 'add to cart');",
		];

		$this->add_script_to_session( $script );
	}

	/**
	 * Voegt variatie-id toe aan remove from cart link
	 *
	 * @param string $link
	 * @param string $cart_item_key
	 * @return string
	 */
	public function add_variation_id_to_cart_item_remove_link( string $link, string $cart_item_key ) {
		$item = WC()->cart->get_cart_item( $cart_item_key );
		$variation_id = $item['variation_id'];

		$link = str_replace( 'data-product_sku', 'data-variation_id="'. $variation_id . '" data-product_sku', $link );
		$bodytag = str_replace("%body%", "black", "<body text='%body%'>");
		return $link;
	}

	/**
	 * Sla script op in sessie om na redirect te kunnen tonen
	 *
	 * @param array $script
	 */
	protected function add_script_to_session( array $script ) {
		WC()->session->set( self::SESSION_SCRIPT_KEY, $script );
		WC()->session->save_data();
	}

	/**
	 * Haal opgeslagen script uit WC-sessie
	 * 
	 * @return array
	 */
	protected function get_script_from_session() {
		$script = WC()->session->get( self::SESSION_SCRIPT_KEY );
		if ( null != $script ) {
			WC()->session->set( self::SESSION_SCRIPT_KEY, null );
			WC()->session->save_data();
		}
		return (array) $script;
	}

}
