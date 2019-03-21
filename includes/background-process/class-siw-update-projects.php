<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om Groepsprojecten bij te werken
 * 
 * @package   SIW\Background-Process
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @uses      SIW_Plato_Import_Workcamps
 * @uses      SIW_WC_Import_Product
 */
class SIW_Update_Workcamps extends SIW_Background_Process {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $batch_size = 50;

	/**
	 * Haal Groepsprojecten op uit Plato
	 *
	 * @return array
	 */
	 protected function select_data() {
		$import = new SIW_Plato_Import_Workcamps;
		$data = $import->run();
		
		return $data;
	}

	/**
	 * Werk Groepsproject bij
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		$import = new SIW_WC_Import_Product( $item );
		$result = $import->process();

		if ( true == $result ) {
			$this->increment_processed_count();
		}

		return false;
	}

}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = [ 'plato' => [ 'title' => __( 'Plato', 'siw' ) ] ];
	$node = [ 'parent' => 'plato', 'title' => __( 'Bijwerken groepsprojecten', 'siw' ) ];
	siw_register_background_process( 'SIW_Update_Workcamps', 'update_workcamps', $node, $parent_nodes, false );
} );

