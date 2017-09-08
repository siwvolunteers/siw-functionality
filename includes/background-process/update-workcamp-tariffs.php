<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Update_Workcamp_Tariffs_Background_Process extends WP_Background_Process {


	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $prefix = 'siw';

	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $action = 'update_workcamp_tariffs_process';

	/**
	 * [task description]
	 * @param  [type] $item [description]
	 * @return [type]       [description]
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

$GLOBALS['siw_update_tariffs_background_process'] = new SIW_Update_Workcamp_Tariffs_Background_Process();
