<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Update_Workcamp_Tariffs_Process extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'update_workcamp_tariffs_process';

	
    /**
     * Task
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

		siw_update_workcamp_tariff( $item );

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
		siw_debug_log( 'Bijwerken tarieven voltooid.' );
	}
}

$GLOBALS['siw_update_workcamp_tariffs_process'] = new SIW_Update_Workcamp_Tariffs_Process();
