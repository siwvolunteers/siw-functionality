<?php declare(strict_types=1);

namespace SIW\WooCommerce;

/**
 * Toevoegen project type
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Product_Type {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'product_type_selector', [ $self, 'set_product_types' ] );
		add_filter( 'woocommerce_product_class', [ $self, 'set_product_class' ], 10, 2 );
	}
 
	/** Voegt product type toe */
	public function set_product_types( array $product_types ): array {
		unset( $product_types['simple'] );
		unset( $product_types['grouped'] );
		unset( $product_types['external'] );

		$product_types[ WC_Product_Project::PRODUCT_TYPE ] = __( 'Project', 'siw' );
		return $product_types;
	}

	/** Zet class voor product type */
	public function set_product_class( string $class, string $product_type ): string {
		if ( WC_Product_Project::PRODUCT_TYPE == $product_type ) {
			$class = WC_Product_Project::class;
		}
		return $class;
	}
}
