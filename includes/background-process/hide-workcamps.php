<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Hide_Workcamps_Process extends SIW_Background_Process {


	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'hide_workcamps_process';


    /**
     * Task
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

		if ( 'publish' != get_post_status( $item ) ) {
			wp_publish_post( $item );
		}
		siw_hide_workcamp( $item );

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
		siw_debug_log( 'Verbergen projecten voltooid');
	}
}

$GLOBALS['siw_hide_workcamps_process'] = new SIW_Hide_Workcamps_Process();
