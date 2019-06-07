<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om Nederlandse Groepsprojecten bij te werken
 * 
 * @package   SIW\Background-Process
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @uses      SIW_Plato_Import_Dutch_Workcamps
 */
class SIW_Update_Dutch_Workcamps extends SIW_Update_Workcamps {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_dutch_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken Nederlandse Groepsprojecten';

	/**
	 * Haal Groepsprojecten op uit Plato
	 *
	 * @return array
	 */
	 protected function select_data() {
		$import = new SIW_Plato_Import_Dutch_Workcamps;
		$data = $import->run();
		
		return $data;
	}

}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'plato' => [ 'title' => __( 'Plato', 'siw' ) ] ];
	$node = [ 'parent' => 'plato', 'title' => __( 'Bijwerken Nederlandse groepsprojecten', 'siw' ) ];
	siw_register_background_process( 'SIW_Update_Dutch_Workcamps', 'update_dutch_workcamps', $node, $parent_nodes, false );
} );

