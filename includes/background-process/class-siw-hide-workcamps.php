<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om Groepsprojecten te verbergen
 * 
 * @package SIW\Background process
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
	 * - Het project begint binnen 7 dagen
	 * - Het project is in een niet-toegestaan land
 	 * - Het project is expliciet verborgen
	 * - Er zijn geen vrije plaatsen meer
	 *
	 * @return array
	 */
	protected function select_data() {
		$limit = date( 'Y-m-d', time() + ( 7 * DAY_IN_SECONDS ) ); //TODO: 7 verplaatsen naar configuratie-constante
	
		$tax_query = array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'slug',
				'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			),
		);
		$meta_query = array(
			'relation'	=>	'OR',
			array(
				'key'		=> 'freeplaces',
				'value'		=> 'no',
				'compare'	=> '='
			),
			array(
				'key'		=> 'manual_visibility',
				'value'		=> 'hide',
				'compare'	=> '='
			),
			array(
				'key'		=> 'startdatum',
				'value'		=> $limit,
				'compare'	=> '<='
			),
			array(
				'key'		=> 'allowed',
				'value'		=> 'no',
				'compare'	=> '='
			),
		);
	
		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'product',
			'meta_query'		=> $meta_query,
			'tax_query'			=> $tax_query,
			'fields' 			=> 'ids',
			'post_status'		=> 'any',
		);
	
		$products = get_posts( $args ); //TODO: wc_get_products
	
		return $products;
	}	

    /**
     * Verberg het Groepsproject
     *
     * @param mixed $item
     *
     * @return bool
     */
	protected function task( $item ) {

		if ( 'publish' != get_post_status( $item ) ) {
			wp_publish_post( $item );
		}
		siw_hide_workcamp( $item );

		$this->increment_processed_count();
		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'workcamps' =>  array( 'title' => __( 'Groepsprojecten', 'siw' ) ),
	);
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Verbergen projecten', 'siw' ) );
	siw_register_background_process( 'SIW_Hide_Workcamps', 'hide_workcamps', $node, $parent_nodes, false );
} );
