<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Delete_Applications_Process extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'delete_applications_process';


    /**
     * Verwijder aanmelding
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

		$order = wc_get_order( $item );
		$order->delete( true );

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();
		siw_debug_log( 'Eind verwijderen aanmeldingen' );
	}
}

$GLOBALS['siw_delete_applications_process'] = new SIW_Delete_Applications_Process();
