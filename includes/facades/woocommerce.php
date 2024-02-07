<?php declare(strict_types=1);

namespace SIW\Facades;

use Exception;
use SIW\WooCommerce\Product\WC_Product_Project;
use WP_Error;

class WooCommerce {

	public static function get_logger(): ?\WC_Logger_Interface {
		if ( ! function_exists( '\wc_get_logger' ) ) {
			return null;
		}
		return \wc_get_logger();
	}

	public static function is_woocommerce(): bool {
		if ( ! function_exists( '\is_woocommerce' ) ) {
			return false;
		}
		return \is_woocommerce();
	}

	public static function is_checkout(): bool {
		if ( ! function_exists( '\is_checkout' ) ) {
			return false;
		}
		return \is_checkout();
	}

	public static function is_cart(): bool {
		if ( ! function_exists( '\is_cart' ) ) {
			return false;
		}
		return \is_cart();
	}

	public static function is_shop(): bool {
		if ( ! function_exists( '\is_shop' ) ) {
			return false;
		}
		return \is_shop();
	}

	public static function is_product_category(): bool {
		if ( ! function_exists( '\is_product_category' ) ) {
			return false;
		}
		return is_product_category();
	}

	public static function is_product_taxonomy(): bool {
		if ( ! function_exists( '\is_product_taxonomy' ) ) {
			return false;
		}
		return is_product_taxonomy();
	}

	public static function get_product_terms( int $product_id, string $taxonomy, array $args = [] ): array {
		if ( ! function_exists( '\wc_get_product_terms' ) ) {
			return false;
		}
		return wc_get_product_terms( $product_id, $taxonomy, $args );
	}

	public static function get_page_permalink( string $page, string $fallback = null ): string {
		if ( ! function_exists( '\wc_get_page_permalink' ) ) {
			return '';
		}
		return wc_get_page_permalink( $page, $fallback );
	}

	public static function get_order( $order ): bool|\WC_Order|\WC_Order_Refund {
		if ( ! function_exists( '\wc_get_order' ) ) {
			return '';
		}
		return wc_get_order( $order );
	}

	/**
	 * @return \WC_Order[]
	 */
	public static function get_orders( array $args ): array {
		if ( ! function_exists( '\wc_get_orders' ) ) {
			return '';
		}
		return wc_get_orders( $args );
	}

	public static function get_coupon_id_by_code( string $code, int $exclude = 0 ): int {
		if ( ! function_exists( '\wc_get_coupon_id_by_code' ) ) {
			return '';
		}
		return wc_get_coupon_id_by_code( $code, $exclude );
	}

	public static function get_product_object( string $product_type, int $product_id = 0 ): \WC_Product {
		if ( ! function_exists( '\wc_get_product_object' ) ) {
			return '';
		}
		return wc_get_product_object( $product_type, $product_id );
	}

	public static function create_attribute( array $args ): int|\WP_Error {
		if ( ! function_exists( 'wc_create_attribute' ) ) {
			return new WP_Error();
		}

		return wc_create_attribute( $args );
	}

	public static function attribute_taxonomy_id_by_name( string $name ): int {
		if ( ! function_exists( 'wc_attribute_taxonomy_id_by_name' ) ) {
			return 0;
		}

		return wc_attribute_taxonomy_id_by_name( $name );
	}

	public static function get_product( $product ): ?WC_Product_Project {
		if ( ! function_exists( '\wc_get_product' ) ) {
			return null;
		}
		$product = \wc_get_product( $product );
		return is_a( $product, WC_Product_Project::class ) ? $product : null;
	}

	/**
	 * @return WC_Product_Project[]
	 */
	public static function get_products( array $args = [] ): array {
		if ( ! function_exists( '\wc_get_products' ) ) {
			return [];
		}
		$args = wp_parse_args(
			$args,
			[
				'return' => 'objects',
				'limit'  => -1,
				'type'   => WC_Product_Project::PRODUCT_TYPE,
			]
		);
		return \wc_get_products( $args );
	}

	/**
	 * @return int[]
	 */
	public static function get_product_ids( array $args = [] ): array {
		if ( ! function_exists( '\wc_get_products' ) ) {
			return [];
		}

		$args = wp_parse_args(
			$args,
			[
				'return' => 'ids',
				'limit'  => -1,
				'type'   => WC_Product_Project::PRODUCT_TYPE,
			]
		);

		return \wc_get_products( $args );
	}

	public static function get_product_by_project_id( string $project_id ): ?WC_Product_Project {
		$args = [
			'project_id' => $project_id,
			'type'       => WC_Product_Project::PRODUCT_TYPE,
		];
		$products = self::get_products( $args );

		return ! empty( $products ) ? reset( $products ) : null;
	}
}
