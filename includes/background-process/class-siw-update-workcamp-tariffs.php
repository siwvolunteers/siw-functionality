<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om tarieven van Groepsprojecten bij te werken
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 * @uses siw_update_workcamp_tariff()
 */
class SIW_Update_Workcamp_Tariffs extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'update_workcamp_tariffs_process';

	/**
	 * @var string
	 */
	protected $name = 'bijwerken tarieven';

	/**
	 * Selecteer alle zichtbare projecten //TODO: misschien gewoon alle projecten selecteren?
	 *
	 * @return array
	 */
	protected function select_data() {
		$args = [
			'visibility' => 'visible',
			'return'     => 'ids',
			'limit'      => -1,
		];
		$products = wc_get_products( $args );
		
		return $products;
	}

	/**
	 * Werk tarieven van het groepsproject bij
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		if ( siw_update_workcamp_tariff( $item ) ) {
			$this->increment_processed_count();
		}
		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'workcamps' => [ 'title' => __( 'Groepsprojecten', 'siw' ) ]	];
	$node = [ 'parent' => 'workcamps', 'title' => __( 'Bijwerken tarieven', 'siw' ) ];
	siw_register_background_process( 'SIW_Update_Workcamp_Tariffs', 'update_workcamp_tariffs', $node, $parent_nodes, true );
} );

