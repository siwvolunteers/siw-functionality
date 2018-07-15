<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Update_Workcamp_Tariffs extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'update_workcamp_tariffs_process';

	/**
	 * Naam
	 *
	 * @var string
	 */
	 protected $name = 'bijwerken tarieven';


	
	protected function select_data() {
		$args = array(
			'visibility'	=> 'visible',
			'return'		=> 'ids',
			'limit'			=> -1,
		);
		$products = wc_get_products( $args );
		
		return $products;
	}

	
    /**
     * Task
     *
     * @param mixed $item Queue item to iterate over.
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
	$parent_nodes = array(
		'workcamps' =>  array( 'title' => __( 'Groepsprojecten', 'siw' ) ),
	);
	$node = array( 'parent' => 'workcamps', 'title' => __( 'Bijwerken tarieven', 'siw' ) );
	siw_register_background_process( 'SIW_Update_Workcamp_Tariffs', 'update_workcamp_tariffs', $node, $parent_nodes, false );
} );

