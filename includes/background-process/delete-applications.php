<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Delete_Applications_Background_Process extends WP_Background_Process {


	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $prefix = 'siw';

	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $action = 'delete_applications_process';

	/**
	 * [task description]
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	protected function task( $item ) {

		wp_delete_post( $item, true );

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

$GLOBALS['siw_delete_applications_background_process'] = new SIW_Delete_Applications_Background_Process();
