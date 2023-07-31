<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor product query
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Query extends Base {

	#[Filter( 'woocommerce_product_data_store_cpt_get_products_query' )]
	public function enable_project_id_search( array $query, array $query_vars ): array {
		if ( ! empty( $query_vars['project_id'] ) ) {
			$query['meta_query'][] = [
				'key'   => '_project_id',
				'value' => esc_attr( $query_vars['project_id'] ),
			];
		}
		return $query;
	}

	#[Filter( 'woocommerce_product_data_store_cpt_get_products_query' )]
	public function enable_country_search( array $query, array $query_vars ): array {
		if ( ! empty( $query_vars['country'] ) ) {
			$query['tax_query'][] = [
				[
					'taxonomy' => Taxonomy_Attribute::COUNTRY()->value,
					'field'    => 'slug',
					'terms'    => esc_attr( $query_vars['country'] ),
				],
			];
		}
		return $query;
	}

	#[Filter( 'woocommerce_product_query_tax_query' )]
	public function set_tax_query( array $tax_query ): array {

		// ESC projecten alleen tonen op ESC overzichtspagina
		if ( ! is_tax( Taxonomy_Attribute::PROJECT_TYPE()->value, 'esc' ) ) {
			$tax_query[] = [
				'taxonomy' => Taxonomy_Attribute::PROJECT_TYPE()->value,
				'terms'    => [ 'esc' ],
				'field'    => 'slug',
				'operator' => 'NOT IN',
			];
		}
		return $tax_query;
	}

}
