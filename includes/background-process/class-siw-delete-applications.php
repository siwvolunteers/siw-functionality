<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Delete_Applications extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'delete_applications_process';

	/**
	 * Naam
	 *
	 * @var string
	 */
	protected $name = 'verwijderen aanmeldingen';

	/**
	 * Zoek de aanmeldingen van meer dan 1 jaar oud
	 *
	 * @return array
	 */
	protected function select_data() {
		$args = array(
			'limit'			=> -1,
			'return'		=> 'ids',
			'type'			=> 'shop_order',
			'date_created'	=> '<' . ( time() - YEAR_IN_SECONDS ),
		);
		$applications = wc_get_orders( $args );

		return $applications;
	}

    /**
     * Verwijder aanmelding
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

		$order = wc_get_order( $item );
		if ( false == $order ) {
			return false;
		}
		$order->delete( true );

		$this->increment_processed_count();
		return false;
	}

}


/* Registreer het background process */
add_action( 'plugins_loaded', function() {
	$parent_nodes = array(
		'applications' =>  array( 'title' => __( 'Aanmeldingen', 'siw' ) ),
	);
	$node = array( 'parent' => 'applications', 'title' => __( 'Verwijderen oude aanmeldingen', 'siw' ) );
	siw_register_background_process( 'SIW_Delete_Applications', 'delete_applications', $node, $parent_nodes );
} );
