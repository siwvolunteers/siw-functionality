<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Attributes\Add_Filter;
use SIW\Base;

class Product_Type extends Base {

	#[Add_Filter( 'product_type_selector' )]
	public function set_product_types( array $product_types ): array {
		unset( $product_types['simple'] );
		unset( $product_types['grouped'] );
		unset( $product_types['external'] );
		unset( $product_types['variable'] );
		$product_types[ WC_Product_Project::PRODUCT_TYPE ] = __( 'Project', 'siw' );
		return $product_types;
	}

	#[Add_Filter( 'woocommerce_product_class' )]
	public function set_product_class( string $class_name, string $product_type ): string {
		if ( WC_Product_Project::PRODUCT_TYPE === $product_type ) {
			$class_name = WC_Product_Project::class;
		}
		return $class_name;
	}
}
