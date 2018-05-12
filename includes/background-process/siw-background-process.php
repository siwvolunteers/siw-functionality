<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class SIW_Background_Process extends WP_Background_Process {
    
    /**
     * Prefix
     *
     * @var string
     * @access protected
     */
    protected $prefix = 'siw';

    /**
     * Empty queue
     *
     * @return $this
     */
    public function empty_queue() {
        $this->data = array();

        return $this;
    }

    /**
     * Task
     *
     * @param mixed $item Queue item to iterate over.
     *
     * @return mixed
     */
	protected function task( $item ) {
		return false;
    }
}