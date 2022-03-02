<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor product query
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Query {

	/** Init */
	public static function init() {

		$self = new self();

		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ $self, 'enable_project_id_search' ], 10, 2 );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ $self, 'enable_country_search' ], 10, 2 );
	}

	/** Voegt project_id als argument toe aan WC queries */
	public function enable_project_id_search( array $query, array $query_vars ): array {
		if ( ! empty( $query_vars['project_id'] ) ) {
			$query['meta_query'][] = [
				'key'   => 'project_id',
				'value' => esc_attr( $query_vars['project_id'] ),
			];
		}
		return $query;
	}
	
	/** Voegt country argument toe aan WC queries */
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
}