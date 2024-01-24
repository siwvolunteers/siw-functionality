<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\WooCommerce\Taxonomy_Attribute;

class Query extends Base {

	#[Add_Filter( 'woocommerce_product_data_store_cpt_get_products_query' )]
	public function enable_project_id_search( array $query, array $query_vars ): array {
		if ( ! empty( $query_vars['project_id'] ) ) {
			$query['meta_query'][] = [
				'key'   => '_project_id',
				'value' => esc_attr( $query_vars['project_id'] ),
			];
		}
		return $query;
	}

	#[Add_Filter( 'woocommerce_product_data_store_cpt_get_products_query' )]
	public function enable_country_search( array $query, array $query_vars ): array {
		if ( ! empty( $query_vars['country'] ) ) {
			$query['tax_query'][] = [
				[
					'taxonomy' => Taxonomy_Attribute::COUNTRY->value,
					'field'    => 'slug',
					'terms'    => esc_attr( $query_vars['country'] ),
				],
			];
		}
		return $query;
	}

	#[Add_Filter( 'woocommerce_product_data_store_cpt_get_products_query' )]
	public function enable_continent_search( array $query, array $query_vars ): array {
		if ( ! empty( $query_vars['continent'] ) ) {
			$query['tax_query'][] = [
				[
					'taxonomy' => Taxonomy_Attribute::CONTINENT->value,
					'field'    => 'slug',
					'terms'    => esc_attr( $query_vars['continent'] ),
				],
			];
		}
		return $query;
	}
}
