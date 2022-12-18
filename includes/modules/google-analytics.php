<?php declare(strict_types=1);

namespace SIW\Modules;

use SIW\Assets\Google_Analytics as Google_Analytics_Asset;
use SIW\Config;
use SIW\HTML;
use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Google Analytics integratie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Google_Analytics {

	const ASSETS_HANDLE = 'siw-analytics';

	const GA_EVENT_CATEGORY = 'Ecommerce';

	// TODO: ENUM van maken
	const ACTION_ADD = 'add';
	const ACTION_CHECKOUT = 'checkout';
	const ACTION_CLICK = 'click';
	const ACTION_DETAIL = 'detail';
	const ACTION_PURCHASE = 'purchase';
	const ACTION_REMOVE = 'remove';

	/** Google Analytics property ID */
	protected ?string $property_id;

	/** Instellingen voor tracker */
	protected array $tracker_settings = [
		'siteSpeedSampleRate' => 100,
		'cookieFlags'         => 'SameSite=None; Secure',
	];

	/** Opties voor tracker */
	protected array $tracker_options = [
		'allowAdFeatures' => false,
		'anonymizeIp'     => true,
		'forceSSL'        => true,
		'transport'       => 'beacon',
	];

	/** Init */
	public static function init() {
		$self = new self();
		$self->set_property_id();

		if ( ! $self->tracking_enabled() ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ] );

		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		add_action( 'woocommerce_before_shop_loop_item', [ $self, 'woocommerce_template_loop_product_link_open' ], 10 );

		add_filter( 'woocommerce_cart_item_remove_link', [ $self, 'add_ga_attributes_to_cart_item_remove_link' ], 10, 2 );
		add_filter( 'siw_woocommerce_add_to_cart_button_attributes', [ $self, 'add_ga_attributes_to_add_to_cart_button' ], 10, 2 );
	}

	/** Haalt het GA property ID op */
	protected function set_property_id() {
		$this->property_id = Config::get_google_analytics_property_id();
	}

	/** Geeft aan of tracking ingeschakeld moet worden */
	protected function tracking_enabled(): bool {
		if ( ! isset( $this->property_id ) || is_user_logged_in() ) {
			return false;
		}
		return true;
	}

	/** Voegt scripts toe */
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/modules/siw-analytics.js', [ Google_Analytics_Asset::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script( self::ASSETS_HANDLE, 'siw_analytics', $this->generate_analytics_data() );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	/** Genereert analytics data */
	protected function generate_analytics_data(): array {
		$analytics_data['property_id'] = $this->property_id;
		$analytics_data['tracker_settings'] = $this->tracker_settings;
		$analytics_data['tracker_options'] = $this->tracker_options;

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$analytics_data['ecommerce_data'] = $this->generate_ecommerce_data();
		}

		return $analytics_data;
	}

	/** Genereert data voor Enhanced Ecommerce */
	protected function generate_ecommerce_data(): array {

		$ecommerce_data = [];
		if ( is_product() ) {
			$product = siw_get_product( get_the_ID() );
			$ecommerce_data['products'][] = $this->get_product_data( $product );
			$ecommerce_data['action'] = self::ACTION_DETAIL;

		} elseif ( is_checkout() && ! is_order_received_page() ) {
			$items = WC()->cart?->get_cart_contents() ?? [];

			foreach ( $items as $item ) {
				$product = siw_get_product( $item['product_id'] );
				$ecommerce_data['products'][] = $this->get_product_data( $product, null, true );
			}
			$ecommerce_data['action'] = self::ACTION_CHECKOUT;

		} elseif ( is_order_received_page() ) {
			$order_id = get_query_var( 'order-received' );
			$order = wc_get_order( $order_id );

			$items = $order->get_items();
			foreach ( $items as $item ) {
				$product = siw_get_product( $item['product_id'] );
				$ecommerce_data['products'][] = $this->get_product_data( $product, null, true );
			}

			$action_data = [
				'id'      => $order->get_id(),
				'revenue' => number_format( floatval( $order->get_total() ), 2 ),
				'coupon'  => implode( ',', $order->get_coupon_codes() ),
			];

			$ecommerce_data['action'] = self::ACTION_PURCHASE;
			$ecommerce_data['action_data'] = $action_data;
		} elseif ( is_shop() || is_product_category() || is_product_taxonomy() ) {
			global $wp_query;
			$product_ids = wp_list_pluck( $wp_query->posts, 'ID' );

			$product_list = $this->determine_product_list();
			$position = 1;
			foreach ( $product_ids as $product_id ) {
				$product = siw_get_product( $product_id );
				$ecommerce_data['impressions'][] = $this->get_impression_data( $product, $product_list, $position );
				$position++;
			}
		}

		return $ecommerce_data;
	}

	/** Geeft productdata voor GA terug */
	protected function get_product_data( WC_Product_Project $product, ?int $position = null, bool $include_quantity = false ): array {

		$category_ids = $product->get_category_ids();
		$category = get_term( $category_ids[0], Taxonomy_Attribute::CONTINENT()->value );

		$product_data = [
			'id'       => $product->get_sku(),
			'name'     => $product->get_formatted_name(),
			'category' => $category->name,
			'brand'    => $product->get_country()->get_name(),
			'price'    => number_format( floatval( $product->get_price() ), 2 ),
			'quantity' => $include_quantity ? 1 : null,
			'position' => $position,
		];

		return array_filter( $product_data );
	}

	/** Genereert impression-data */
	protected function get_impression_data( WC_Product_Project $product, string $list, int $position ): array {
		$impression_data = $this->get_product_data( $product, $position );
		$impression_data['list'] = $list;

		return $impression_data;
	}

	/** Bepaalt productlijst */
	protected function determine_product_list(): string {

		$product_list = 'Onbekend';

		if ( is_product_category() || is_product_taxonomy() ) {
			$queried_object = get_queried_object();
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			$product_list = sprintf(
				'Projecten per %s: %s',
				$taxonomy->labels->singular_name,
				$queried_object->name
			);
		} elseif ( \is_shop() ) {
			$queried_object = get_queried_object();
			$product_list = $queried_object->label;
		} elseif ( is_page() ) {
			$queried_object = get_queried_object();
			$product_list = sprintf( 'Pagina: %s', $queried_object->post_title );
		}
		return $product_list;
	}

	/** Voegt GA attributes toe aan WooCommerce Loop link */
	public function woocommerce_template_loop_product_link_open() {
		global $product;
		static $position = 0;

		$attributes = [
			'href'                => apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'class'               => 'woocommerce-LoopProduct-link woocommerce-loop-product__link',
			'data-ga-track'       => 1,
			'data-ga-type'        => 'event',
			'data-ga-category'    => self::GA_EVENT_CATEGORY,
			'data-ga-action'      => self::ACTION_CLICK,
			'data-ga-label'       => 'Results',
			'data-ec-action'      => self::ACTION_CLICK,
			'data-ec-action-data' => [ 'list' => $this->determine_product_list() ],
			'data-ec-product'     => $this->get_product_data( $product, ++$position ),
		];

		printf(
			'<a %s>',
			HTML::generate_attributes( $attributes ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/** Voegt GA attributes toe aan remove from cart link */
	public function add_ga_attributes_to_cart_item_remove_link( string $link, string $cart_item_key ) : string {
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );
		/** @var WC_Product_Project */
		$product = $cart_item['data'];

		$attributes = [
			'data-ga-track'    => 1,
			'data-ga-type'     => 'event',
			'data-ga-category' => self::GA_EVENT_CATEGORY,
			'data-ga-action'   => self::ACTION_REMOVE,
			'data-ga-label'    => 'remove from cart',
			'data-ec-action'   => self::ACTION_REMOVE,
			'data-ec-product'  => $this->get_product_data( $product, null, true ),
		];

		$link = str_replace( 'href', HTML::generate_attributes( $attributes ) . ' href', $link );

		return $link;
	}

	/** TODO: */
	public function add_ga_attributes_to_add_to_cart_button( array $attributes, WC_Product_Project $product ): array {
		$attributes = [
			'data-ga-track'    => 1,
			'data-ga-type'     => 'event',
			'data-ga-category' => self::GA_EVENT_CATEGORY,
			'data-ga-action'   => self::ACTION_ADD,
			'data-ga-label'    => 'add to cart',
			'data-ec-action'   => self::ACTION_ADD,
			'data-ec-product'  => $this->get_product_data( $product, null, true ),
		];

		return $attributes;
	}

}
