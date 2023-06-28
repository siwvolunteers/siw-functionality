<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Assets\Google_Analytics_4 as Google_Analytics_4_Asset;
use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Compatibility\WooCommerce;
use SIW\Config;
use SIW\Util\HTML;
use SIW\WooCommerce\Product\WC_Product_Project;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Google Analytics 4
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @link      https://developers.google.com/analytics/devguides/collection/ga4
 */
class Google_Analytics_4 extends Base {

	const ASSETS_HANDLE = 'siw-google-analytics-4';

	// TODO: enum van maken
	const EVENT_ADD_TO_CART = 'add_to_cart';
	const EVENT_BEGIN_CHECKOUT = 'begin_checkout';
	const EVENT_SELECT_ITEM = 'select_item';
	const EVENT_VIEW_ITEM = 'view_item';
	const EVENT_PURCHASE = 'purchase';
	const EVENT_REMOVE_FROM_CART = 'remove_from_cart';
	const EVENT_VIEW_CART = 'view_cart';
	const EVENT_VIEW_ITEM_LIST = 'view_item_list';

	#[Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		if ( is_user_logged_in() || null === Config::get_google_analytics_measurement_id() ) {
			return;
		}
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/features/google-analytics-4.js', [ Google_Analytics_4_Asset::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script( self::ASSETS_HANDLE, 'siw_google_analytics_4', $this->generate_analytics_data() );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	protected function generate_analytics_data(): array {
		$analytics_data['measurement_id'] = Config::get_google_analytics_measurement_id();
		$analytics_data['config'] = $this->get_config();
		if ( is_plugin_active( WooCommerce::get_plugin_basename() ) ) {
			$analytics_data['ecommerce_event'] = $this->generate_ecommerce_event_data();
		}

		return array_filter( $analytics_data );
	}

	protected function get_config(): array {
		$config = [
			'allow_ad_personalization_signals' => false,
			'allow_google_signals'             => false,
			'cookie_flags'                     => 'SameSite=None;Secure',
		];
		if ( WP_DEBUG ) {
			$config['debug_mode'] = true;
		}
		if ( is_plugin_active( WooCommerce::get_plugin_basename() ) ) {
			$config['currency'] = get_woocommerce_currency();
		}
		return $config;
	}

	/** Genereert data voor Ecommerce event */
	protected function generate_ecommerce_event_data(): array {
		$ecommerce_event = [];
		if ( is_product() ) {
			$product = siw_get_product( get_the_ID() );
			$ecommerce_event['name'] = self::EVENT_VIEW_ITEM;
			$ecommerce_event['parameters']['items'][] = $this->get_product_data( $product );

		} elseif ( is_cart() || ( is_checkout() && ! is_order_received_page() ) ) {
			$items = WC()->cart?->get_cart_contents() ?? [];

			$ecommerce_event['name'] = is_cart() ? self::EVENT_VIEW_CART : self::EVENT_BEGIN_CHECKOUT;
			$ecommerce_event['parameters'] = [
				'value'  => number_format( floatval( WC()->cart?->get_total( 'edit' ) ), 2 ),
				'coupon' => implode( ',', WC()->cart?->get_applied_coupons() ),
			];

			foreach ( $items as $item ) {
				$product = siw_get_product( $item['product_id'] );

				$discount = (float) $item['line_subtotal'] - (float) $item['line_total'];
				$product_data = $this->get_product_data( $product );
				$product_data['discount'] = number_format( $discount, 2 );

				$ecommerce_event['parameters']['items'][] = $product_data;
			}
		} elseif ( is_order_received_page() ) {
			$order_id = get_query_var( 'order-received' );
			$order = wc_get_order( $order_id );

			$ecommerce_event['name'] = self::EVENT_PURCHASE;
			$ecommerce_event['parameters'] = [
				'transaction_id' => $order->get_order_number(),
				'value'          => number_format( floatval( $order->get_total() ), 2 ),
				'coupon'         => implode( ',', $order->get_coupon_codes() ),
			];

			/** @var \WC_Order_Item_Product[] */
			$items = $order->get_items();
			foreach ( $items as $item ) {
				$product = siw_get_product( $item->get_product_id() );

				$discount = (float) $item->get_subtotal() - (float) $item->get_total();
				$product_data = $this->get_product_data( $product );
				$product_data['discount'] = number_format( $discount, 2 );
				$ecommerce_event['parameters']['items'][] = $product_data;
			}
		} elseif ( is_shop() || is_product_category() || is_product_taxonomy() ) {
			global $wp_query;
			$product_ids = wp_list_pluck( $wp_query->posts, 'ID' );

			$ecommerce_event['name'] = self::EVENT_VIEW_ITEM_LIST;
			$ecommerce_event['parameters'] = [
				'item_list_id'   => $this->determine_item_list_id(),
				'item_list_name' => $this->determine_item_list_name(),
			];

			$index = 0;
			foreach ( $product_ids as $product_id ) {
				$product = siw_get_product( $product_id );
				$ecommerce_event['parameters']['items'][] = $this->get_product_data( $product, $index );
				++$index;
			}
		}

		return $ecommerce_event;
	}

	/** Geeft productdata voor GA terug */
	protected function get_product_data( WC_Product_Project $product, int $index = null ): array {

		$category_ids = $product->get_category_ids();
		$category = get_term( $category_ids[0], Taxonomy_Attribute::CONTINENT()->value );

		$product_data = [
			'id'             => $product->get_sku(),
			'name'           => $product->get_formatted_name(),
			'item_category'  => $category->name,
			'item_category2' => $product->get_country()->get_name(),
			'price'          => number_format( floatval( $product->get_price() ), 2 ),
			'index'          => $index,
		];

		return array_filter( $product_data );
	}

	protected function determine_item_list_id(): string {
		return $this->determine_item_list()['id'];
	}

	protected function determine_item_list_name(): string {
		return $this->determine_item_list()['name'];
	}

	/** Bepaalt productlijst */
	protected function determine_item_list(): array {

		$id = 'onbekend';
		$name = 'Onbekend';

		if ( is_product_category() || is_product_taxonomy() ) {
			/** @var \WP_Term */
			$queried_object = get_queried_object();
			$taxonomy = get_taxonomy( $queried_object->taxonomy );
			$id = $queried_object->taxonomy . ' ' . $queried_object->slug;
			$name = sprintf(
				'Projecten per %s: %s',
				$taxonomy->labels->singular_name,
				$queried_object->name
			);
		} elseif ( \is_shop() ) {
			/** @var \WP_Post_Type */
			$queried_object = get_queried_object();
			$id = $queried_object->name;
			$name = $queried_object->label;
		} elseif ( is_page() ) {
			/** @var \WP_Post */
			$queried_object = get_queried_object();
			$id = $queried_object->guid;
			$name = sprintf( 'Pagina: %s', $queried_object->post_title );
		}
		return [
			'id'   => $id,
			'name' => $name,
		];
	}

	#[Action( 'woocommerce_before_shop_loop_item', 9 )]
	/** Voegt GA attributes toe aan WooCommerce Loop link */
	public function woocommerce_template_loop_product_link_open() {
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

		global $product;
		static $index = 0;

		$attributes = [
			'href'           => apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
			'class'          => 'woocommerce-LoopProduct-link woocommerce-loop-product__link',
			'data-ga4-event' => [
				'name'       => self::EVENT_SELECT_ITEM,
				'parameters' => [
					'item_list_id'   => $this->determine_item_list_id(),
					'item_list_name' => $this->determine_item_list_name(),
					'items'          => [ $this->get_product_data( $product, $index ) ],
				],
			],
		];

		++$index;

		printf(
			'<a %s>',
			HTML::generate_attributes( $attributes ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	#[Filter( 'woocommerce_cart_item_remove_link' )]
	public function add_ga_attributes_to_cart_item_remove_link( string $link, string $cart_item_key ): string {
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );
		/** @var WC_Product_Project */
		$product = $cart_item['data'];

		$attributes['data-ga4-event'] = $this->generate_cart_event_attributes( $product, self::EVENT_REMOVE_FROM_CART );

		$processor = new \WP_HTML_Tag_Processor( $link );
		if ( $processor->next_tag() ) {
			foreach ( $attributes as $attribute => $value ) {
				if ( is_array( $value ) ) {
					$value = wp_json_encode( $value );
				}
				$processor->set_attribute( $attribute, $value );
			}
		}

		return $processor->get_updated_html();
	}

	#[Filter( 'siw_woocommerce_add_to_cart_button_attributes' )]
	public function add_ga_attributes_to_add_to_cart_button( array $attributes, WC_Product_Project $product ): array {
		$attributes['data-ga4-event'] = $this->generate_cart_event_attributes( $product, self::EVENT_ADD_TO_CART );
		return $attributes;
	}

	protected function generate_cart_event_attributes( WC_Product_Project $product, string $event_name ): array {
		return [
			'name'       => $event_name,
			'parameters' => [
				'value' => number_format( floatval( $product->get_price() ), 2 ),
				'items' => [ $this->get_product_data( $product, null ) ],
			],
		];
	}

}
