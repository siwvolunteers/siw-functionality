<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\WooCommerce\WC_Product_Project;

/**
 * Google Analytics integratie
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Google_Analytics {

	/** Key voor WooCommerce-sessie */
	const SESSION_SCRIPT_KEY = 'siw_enhanced_ecommerce_script';

	/** Google Analytics property ID */
	protected string $property_id;

	/** Instellingen voor tracker */
	protected array $tracker_settings = [
		'anonymizeIp' => true,
		'forceSSL'    => true,
	];

	/** Init */
	public static function init() {
		$self = new self();
		$self->set_property_id();

		if ( ! $self->tracking_enabled() ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ] );
		add_action( 'woocommerce_add_to_cart', [ $self, 'track_add_to_cart'], 10, 6 );

		add_filter( 'rocket_minify_excluded_external_js', [ $self, 'add_ga_url' ] );
		add_filter( 'rocket_exclude_defer_js', [ $self, 'add_ga_url'] );
		add_filter( 'siw_preconnect_urls', [ $self, 'add_ga_url'] );
		add_filter( 'rocket_excluded_inline_js_content', [ $self, 'set_excluded_inline_js_content' ] );
	}

	/** Haalt het GA property ID op */
	protected function set_property_id() {
		$this->property_id = siw_get_option( 'google_analytics.property_id' );
	}

	/** Geeft aan of tracking ingeschakeld moet worden */
	protected function tracking_enabled() : bool {
		if ( ! isset( $this->property_id ) || is_user_logged_in() ) {
			return false;
		}
		return true;
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_enqueue_script( 'google-analytics', 'https://www.google-analytics.com/analytics.js', [], null, true );
		wp_register_script( 'siw-analytics', SIW_ASSETS_URL . 'js/modules/siw-analytics.js', [ 'google-analytics' ], SIW_PLUGIN_VERSION, true );
		wp_localize_script( 'siw-analytics', 'siw_analytics_cart', $this->generate_cart_data() );
		wp_enqueue_script( 'siw-analytics' );
		wp_add_inline_script( 'google-analytics', $this->generate_snippet(), 'after' );
	}

	/** Genereert snippet */
	protected function generate_snippet() : string {
		$snippet = [
			"window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;",
			sprintf( "ga('create','%s',{'siteSpeedSampleRate': 100, 'cookieFlags': 'SameSite=None; Secure'});", esc_js( $this->property_id ) ),
		];
		foreach ( $this->tracker_settings as $setting => $value ) {
			$snippet[] = sprintf( "ga('set', '%s', %s);", esc_js( $setting ), esc_js( $value ) );
		}

		$snippet = array_merge( $snippet, $this->generate_ecommerce_script() );
		$snippet[] = "ga('send','pageview');";
		$snippet = array_merge( $snippet, $this->get_script_from_session() );
		
		return implode( PHP_EOL, $snippet );
	}
	
	/** Genereert Enhanced Ecommerce script */
	protected function generate_ecommerce_script() : array {

		if ( is_product() ) {
			$product = siw_get_product( get_the_ID() );
			if ( null == $product ) {
				return [];
			}

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
				$product_data = $this->get_product_data( $product );
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
				$product_data = $this->get_product_data( $product );
				$ecommerce_script[] = sprintf( "ga('ec:addProduct', %s);", json_encode( $product_data ) );
			}

			$order_data = [ 
				'id'     => $order->get_id(),
				'revenue' => number_format( floatval( $order->get_total() ), 2 ),
				'coupon'  => implode( ',', $order->get_coupon_codes() ),
			];
			$ecommerce_script[] = sprintf( "ga('ec:setAction', 'purchase', %s);", json_encode( $order_data ) );
		}
		else {
			return [];
		}
		return $ecommerce_script;
	}

	/** Geeft productdata voor GA terug */
	protected function get_product_data( WC_Product_Project $product ) : array {

		$category_ids = $product->get_category_ids();
		$category = get_term( $category_ids[0], 'product_cat' );

		$product_data = [
			'id'       => esc_js( $product->get_sku() ),
			'name'     => esc_js( $product->get_title() ),
			'category' => esc_js( $category->name ),
			'price'    => number_format( (float) $product->get_price(), 2 ),
			'quantity' => 1,
		];

		return $product_data;
	}

	/** Genereert cart data (om verwijderen uit cart te tracken) */
	protected function generate_cart_data() {
		$items = WC()->cart->get_cart_contents();
		$cart_data = [];
		foreach ( $items as $key => $item ) {
			$product = wc_get_product( $item['product_id'] );
			//$cart_data[ $item['variation_id'] ] = $this->get_product_data( $product );
		}
		return $cart_data;
	}

	/** Track toevoegen aan cart */
	public function track_add_to_cart( string $cart_item_key, int $product_id, int $quantity, int $variation_id, array $variation, array $cart_item_data ) {
		$product = siw_get_product( $product_id );
		if ( null == $product ) {
			return;
		}

		$product_data = $this->get_product_data( $product );
		
		$script = [
			"ga('require', 'ec');",
			sprintf( "ga('ec:addProduct', %s);", json_encode( $product_data ) ),
			"ga('ec:setAction', 'add');",
			"ga('send', 'event', 'Ecommerce', 'add', 'add to cart');",
		];

		$this->add_script_to_session( $script );
	}

	/** Sla script op in sessie om na redirect te kunnen tonen */
	protected function add_script_to_session( array $script ) {
		WC()->session->set( self::SESSION_SCRIPT_KEY, $script );
		WC()-> session->save_data();
	}

	/** Haal opgeslagen script uit WC-sessie */
	protected function get_script_from_session() : array {
		$script = WC()->session->get( self::SESSION_SCRIPT_KEY );
		if ( null != $script ) {
			WC()->session->set( self::SESSION_SCRIPT_KEY, null );
			WC()->session->save_data();
		}
		return (array) $script;
	}


	/**
	 * Voegt GA-domein aan array toe t.b.v. filters
	 * 
	 * - Uitsluiten van minification
	 * - Uitsluiten van defer
	 * - Resource hints (dns-prefetch + preconnect)
	 *
	 * @param array $urls
	 * @return array
	 */
	public function add_ga_url( array $urls ) : array {
		$urls[] = 'https://www.google-analytics.com/';
		return $urls;
	}

	/** Sluit inline JS voor Ecommerce uit van combineren */
	public function set_excluded_inline_js_content( array $content ) : array {
		$content[] = 'ec:';
		return $content;
	}
}
