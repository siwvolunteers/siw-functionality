<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Delete_Orphaned_Variations extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'delete_orphaned_variations';

	/**
	 * Naam
	 *
	 * @var string
	 */
	protected $name = 'verwijderen verweesde variaties';

	/**
	 * Undocumented function
	 *
	 * @return array
	 */
	 protected function select_data() {
		$args = array(
			'posts_per_page'		=> -1,
			'post_type'				=> 'product',
			'fields'				=> 'ids',
			'post_status'			=> 'any',
		);
		$products = get_posts( $args );
	
		//zoek alle product_variations zonder parent.
		$args = array(
			'posts_per_page'		=> -1,
			'post_type'				=> 'product_variation',
			'post_parent__not_in'	=> $products,
			'fields' 				=> 'ids',
		);
		$variations = get_posts( $args );
	
		if ( empty( $variations ) ) {
			return false;
		}
	
	
		//wp all import tabel bijwerken
		global $wpdb;
		if ( ! isset( $wpdb->pmxi_posts ) ) {
			$wpdb->pmxi_posts = $wpdb->prefix . 'pmxi_posts';
		}
	
		$variation_ids = implode( ',', $variations );
		$wpdb->query(
			$wpdb->prepare("
				DELETE FROM $wpdb->pmxi_posts
				WHERE post_id IN (%s)",
				$variation_ids
			)
		);

		return $variations;
	}


    /**
     * Verwijderen alle variaties
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

		$product = wc_get_product( $item );
		if ( false == $product ) {
			return false;
		}
		$product->delete( true );

		return false;
	}

}


/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'workcamps' =>  array( 'title' => __( 'Groepsprojecten', 'siw' ) ),
	);
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Verwijderen verweesde variaties', 'siw' ) );
	siw_register_background_process( 'SIW_Delete_Orphaned_Variations', 'delete_orphaned_variations', $node, $parent_nodes, false );
} );
