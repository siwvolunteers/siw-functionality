<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om Groepsprojecten te verbergen
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Hide_Workcamps extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'hide_workcamps_process';

	/**
	 * @var string
	 */
	protected $name = 'verbergen groepsprojecten';	

	/**
	 * Groepsprojecten selecteren die aan 1 of meer van onderstaande voorwaarden voldoen:
	 *
	 * - Het project begint binnen 7 dagen
	 * - Het project is in een niet-toegestaan land
	 * - Er zijn geen vrije plaatsen meer
	 *
	 * @todo configuratieconstante voor aantal dagen
	 * @todo wc_get_products gebruiken
	 * @return array
	 */
	protected function select_data() {
		$limit = date( 'Y-m-d', time() + ( 7 * DAY_IN_SECONDS ) );
	
		$tax_query = [
			[
				'taxonomy' => 'product_visibility',
				'field'    => 'slug',
				'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
				'operator' => 'NOT IN',
			],
		];
		$meta_query = [
			'relation' => 'OR',
			[
				'key'     => 'freeplaces',
				'value'   => 'no',
				'compare' => '='
			],
			[
				'key'     => 'start_date',
				'value'   => $limit,
				'compare' => '<='
			],
		];
	
		$args = [
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'meta_query'     => $meta_query,
			'tax_query'      => $tax_query,
			'fields'         => 'ids',
			'post_status'    => 'any',
		];
	
		$products = get_posts( $args );
	
		return $products;
	}	

	/**
	 * Verberg het Groepsproject
	 *
	 * @param mixed $product_id
	 *
	 * @return bool
	 */
	protected function task( $product_id ) {

		$product = wc_get_product( $product_id );
		
		if ( false == $product ) {
			return false;
		}

		if ( 'publish' != get_post_status( $product_id ) ) {
			wp_publish_post( $product_id );
		}

		$product->set_catalog_visibility( 'hidden' );
		$product->set_featured( false );
		SIW_Util::set_seo_noindex( $product_id, true );
		$product->save();

		$this->increment_processed_count();
		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'workcamps' => [ 'title' => __( 'Groepsprojecten', 'siw' ) ] ];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Verbergen projecten', 'siw' ) ];
	siw_register_background_process( 'SIW_Hide_Workcamps', 'hide_workcamps', $node, $parent_nodes, true );
} );
