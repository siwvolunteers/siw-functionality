<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Hide_Workcamps_Background_Process extends WP_Background_Process {


	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $prefix = 'siw';

	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $action = 'hide_workcamps_process';

	/**
	 * [task description]
	 * @param  [type] $item [description]
	 * @return [type]       [description]
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

$GLOBALS['siw_hide_workcamps_background_process'] = new SIW_Hide_Workcamps_Background_Process();
