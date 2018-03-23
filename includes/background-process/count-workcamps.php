<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Count_Workcamps_Background_Process extends WP_Background_Process {


	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $prefix = 'siw';

	/**
	 * [protected description]
	 * @var [type]
	 */
	protected $action = 'count_workcamps_process';

	/**
	 * [task description]
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	protected function task( $item ) {

        $taxonomy = $item['taxonomy'];
        $term_slug = $item['term_slug']; 
		siw_count_projets_by_term( $taxonomy, $term_slug );

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
		siw_debug_log( 'Eind tellen projecten' );
	}
}

$GLOBALS['siw_count_workcamps_background_process'] = new SIW_Count_Workcamps_Background_Process();
