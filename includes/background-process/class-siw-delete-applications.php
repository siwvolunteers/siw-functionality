<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Proces om oude aanmeldingen te verwijderen
 * 
 * @package SIW\Background process
 * @author Maarten Bruna
 * @copyright 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Delete_Applications extends SIW_Background_Process {

	/**
	 * @var string
	 */
	protected $action = 'delete_applications_process';

	/**
	 * @var string
	 */
	protected $name = 'verwijderen aanmeldingen';

	/**
	 * Selecteer de aanmeldingen van meer dan 1 jaar oud //TODO: geannuleerde aanmeldingen van meer dan 1 maand oud
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
     * @param mixed $item
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
