<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SIW_Count_Workcamps_Process extends SIW_Background_Process {

	/**
	 * Action
	 *
	 * @var string
	 * @access protected
	 */
	protected $action = 'count_workcamps_process';

	
    /**
     * Task
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {

        $taxonomy = $item['taxonomy'];
        $term_slug = $item['term_slug']; 
		siw_count_projects_by_term( $taxonomy, $term_slug, true );
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

$GLOBALS['siw_count_workcamps_process'] = new SIW_Count_Workcamps_Process();
