<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Repair_Workcamps_Background_Process extends WP_Background_Process {


	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $prefix = 'siw';

	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $action = 'repair_workcamps_process';

	/**
	 * [task description]
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	protected function task( $item ) {

		siw_repair_project( $item );

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
		siw_debug_log( 'Repareren projecten voltooid.' );
	}
}

$GLOBALS['siw_repair_workcamps_background_process'] = new SIW_Repair_Workcamps_Background_Process();
