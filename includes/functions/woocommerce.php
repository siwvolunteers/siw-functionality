<?php declare(strict_types=1);

use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Wrapper functions om WooCommerce functies
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */


/** Wrapper om wc_get_product */
function siw_get_product( $product ): ?WC_Product_Project {
	if ( ! function_exists( '\wc_get_product') ) {
		return null;
	}
	$product = wc_get_product( $product );
	return is_a( $product, WC_Product_Project::class ) ? $product : null;
}

/**
 * Wrapper om wc_get_products
 * 
 * @return WC_Product_Project[]
 */
function siw_get_products( array $args = [] ): array {
	if ( ! function_exists( '\wc_get_products') ) {
		return [];
	}
	$args = wp_parse_args(
		$args,
		[
			'return' => 'objects',
			'limit'  => -1,
		]
	);
	return wc_get_products( $args );
}

/**
 * Wrapper om wc_get_products
 * 
 * @return int[]
 */
function siw_get_product_ids( array $args = [] ): array {
	if ( ! function_exists( '\wc_get_products') ) {
		return [];
	}

	$args = wp_parse_args(
		$args,
		[
			'return' => 'ids',
			'limit'  => -1,
		]
	);

	return wc_get_products( $args );
}

/** Zoekt product o.b.v. project_id */
function siw_get_product_by_project_id( string $project_id ): ?WC_Product_Project {
	$args = [
		'project_id' => $project_id,
	];
	$products = siw_get_products( $args );

	return ! empty( $products ) ? reset( $products ) : null;
}