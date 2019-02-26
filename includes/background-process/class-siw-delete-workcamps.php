<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om oude Groepsprojecten te verwijderen
 * 
 * @package SIW\Background-Process
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
	 * @todo configuratieconstante voor aantal maanden
	 * @todo wc_get_products gebruiken
	 *
	 * @return array
	 */
	protected function select_data() {
		$limit = date( 'Y-m-d', time() - ( 6 * MONTH_IN_SECONDS ) );

		$meta_query = [
			'relation'	=> 'OR',
			[
				'key'     => 'startdatum',
				'value'   => $limit,
				'compare' => '<',
			],
			[
				'key'     => 'startdatum', //TODO: meta wordt gewoon startdate
				'compare' => 'NOT EXISTS',
			],
		];
		$args = [
			'posts_per_page' => -1,
			'post_type'      => 'product',
			'meta_query'     => $meta_query,
			'fields'         => 'ids',
			'post_status'    => 'any',
		];
		$products = get_posts( $args );
		
		if ( empty( $products ) ) {
			return false;
		}
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
	$parent_nodes = [
		'workcamps' => [ 'title' => __( 'Groepsprojecten', 'siw' ) ],
	];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Verwijderen oude projecten', 'siw' ) ];
	siw_register_background_process( 'SIW_Delete_Workcamps', 'delete_workcamps', $node, $parent_nodes, true );
} );
