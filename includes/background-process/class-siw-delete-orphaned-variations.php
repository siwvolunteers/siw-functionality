<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om verweesde variaties te verwijderen
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Delete_Orphaned_Variations extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'delete_orphaned_variations';

	/**
	 * @var string
	 */
	protected $name = 'verwijderen verweesde variaties';

	/**
	 * Selecteer alle verweesde variaties
	 *
	 * @return array
	 */
	 protected function select_data() {
		$args = [
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'fields'         => 'ids',
			'post_status'    => 'any',
		];
		$products = get_posts( $args );
	
		//zoek alle product_variations zonder parent.
		$args = [
			'posts_per_page'      => -1,
			'post_type'           => 'product_variation',
			'post_parent__not_in' => $products,
			'fields'              => 'ids',
		];
		$variations = get_posts( $args );
	
		if ( empty( $variations ) ) {
			return false;
		}

		return $variations;
	}

	/**
	 * Verwijderen van verweesde variaties
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
		$product->delete( true );

		return false;
	}

}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'workcamps' => [ 'title' => __( 'Groepsprojecten', 'siw' ) ] ];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Verwijderen verweesde variaties', 'siw' ) ];
	siw_register_background_process( 'SIW_Delete_Orphaned_Variations', 'delete_orphaned_variations', $node, $parent_nodes, false );
} );
