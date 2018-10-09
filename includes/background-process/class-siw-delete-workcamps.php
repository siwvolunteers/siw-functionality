<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om oude Groepsprojecten te verwijderen
 * 
 * @package SIW\Background process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Delete_Workcamps extends SIW_Background_Process {

	/**
	 * @access protected
	 */
	protected $action = 'delete_workcamps_process';

	/**
	 * @var string
	 */
	protected $name = 'verwijderen groepsprojecten';

	/**
     * Selecteer Groepsprojecten die meer dan 6 maanden geleden zijn begonnen
     *
	 * @return array
	 */
	protected function select_data() {
		$limit = date( 'Y-m-d', time() - ( 6 * MONTH_IN_SECONDS ) ); //TODO:configuratieconstante voor aantal maanden

		$meta_query = array(
			'relation'	=> 'OR',
			array(
				'key'		=> 'startdatum',
				'value'		=> $limit,
				'compare'	=> '<',
			),
			array(
				'key'		=> 'startdatum',
				'compare'	=> 'NOT EXISTS',
			),
		);
		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'product',
			'meta_query'		=> $meta_query,
			'fields' 			=> 'ids'
		);
		$products = get_posts( $args ); //TODO: wc_get_products gebruiken
	
		
		if ( empty( $products ) ) {
			
			return false;
		}
	
		//variaties van geselecteerde projecten opzoeken //TODO: kan weg na vervangen WP All Import
		$args = array(
			'posts_per_page'	=> -1,
			'post_type'			=> 'product_variation',
			'post_parent__in'	=> $products,
			'fields' 			=> 'ids',
		);
		$variations = get_posts( $args );
	
		//variaties en producten samenvoegen tot 1 array voor DELETE-query
		$posts = array_merge( $variations, $products );
		$post_ids = implode( ',', $posts );
	
		//wp all import tabel bijwerken
		global $wpdb;
		if ( ! isset( $wpdb->pmxi_posts ) ) {
			$wpdb->pmxi_posts = $wpdb->prefix . 'pmxi_posts';
		}
	
		$wpdb->query(
			$wpdb->prepare("
				DELETE FROM $wpdb->pmxi_posts
				WHERE post_id IN (%s)",
				$post_ids
			)
		);

		return $products;
	}

    /**
     * Verwijderen van product (inclusief variaties)
     *
     * @param mixed $item
     *
     * @return mixed
     */
	protected function task( $item ) {

		$product = wc_get_product( $item );
		if ( false == $product ) {
			return false;
		}
		$variations = $product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( false == $variation ) {
				continue;
			}
			$variation->delete( true );
		}
		$product->delete( true );

		$this->increment_processed_count();

		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'workcamps' =>  array( 'title' => __( 'Groepsprojecten', 'siw' ) ),
	);
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Verwijderen oude projecten', 'siw' ) );
	siw_register_background_process( 'SIW_Delete_Workcamps', 'delete_workcamps', $node, $parent_nodes, true );
} );
