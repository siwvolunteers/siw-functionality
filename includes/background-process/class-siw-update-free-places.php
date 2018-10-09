<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om vrije plaatsen van Groepsprojecten bij te werken
 * 
 * @package SIW\Background process
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @uses \SIW_Plato_Import_FPL
 */
class SIW_Update_Free_Places extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'update_free_places';

	/**
	 * @var string
	 */
	protected $name = 'bijwerken vrije plaatsen';		

	/**
	 * Hulpfunctie om te bepalen of er nog vrije plaatsen op het project zijn.
	 *
	 * @param int $free_m
	 * @param int $free_f
	 * @param string $no_more_from
	 * @return boolean
	 */	
	protected function has_free_places( $free_m, $free_f, $no_more_from ) {
		$free_m = (int) $free_m;
		$free_f = (int) $free_f;
	
		return ( ( $free_m + $free_f ) > 0 && ( false === strpos( $no_more_from, 'NLD' ) ) ) ? 'yes' : 'no';
	}

	/**
	 * Haal vrije plaatsen lijst uit Plato op
	 * 
	 * @return array
	 */
	protected function select_data() {
		$import = new SIW_Plato_Import_FPL;
		$data = $import->run();
		
		return $data;
	}
	
    /**
     * Werk aantal vrije plaatsen bij
     *
     * @param mixed $item
     *
     * @return mixed
     */
	protected function task( $item ) {

		$free_places = $this->has_free_places( $item['free_m'], $item['free_f'], $item['no_more_from'] );
		if ( 'yes' == $free_places ) {
			return false;
		}
	
		$args = array(
			'visibility'	=> 'visible',
			'project_id'	=> $item['project_id'],
			'return'		=> 'objects',
			'limit'			=> -1,
		);
		$products = wc_get_products( $args );
	
		//Afbreken als project niet gevonden wordt.
		if ( empty( $products ) ) {
			return false;
		}

		$product = $products[0];
		$product->update_meta_data( 'freeplaces', $free_places );
		$product->save();
		$this->increment_processed_count();

		return false;
	}
}

/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'plato' =>  array( 'title' => __( 'Plato', 'siw' ) ),
	);
	$node = array( 'parent' => 'plato', 'title' => __( 'Bijwerken vrije plaatsen', 'siw' ) );
	siw_register_background_process( 'SIW_Update_Free_Places', 'update_free_places', $node, $parent_nodes, false );
} );
