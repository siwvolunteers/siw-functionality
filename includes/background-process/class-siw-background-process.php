<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Uitbreiding van WP_Background_Process
 * - Logging
 * - Aantal verwerkte items bijhouden
 */
abstract class SIW_Background_Process extends WP_Background_Process {
    
    /**
     * Prefix
     *
     * @var string
     * @access protected
     */
    protected $prefix = 'siw';

    /**
     * Optie-naam voor logger-context
     *
     * @var string
     */
    protected $logger_context_option;

    /**
     * Optie-naam voor teller verwerkte items
     *
     * @var string
     */
    protected $processed_count_option;

    /**
     * Naam van proces (voor logging)
     *
     * @var string
     */
    protected $name;

    /**
     * Undocumented variable
     *
     * @var integer
     */
    protected $batch_size = 500;

    /**
	 * Initiate new background process
	 */
	public function __construct() {
        parent::__construct();
        $this->logger_context_option = $this->identifier . '_logger_context';
        $this->processed_count_option = $this->identifier . '_processed_count';
    }

    /**
     * Leeg de queue
     *
     * @return $this
     */
    protected function empty_queue() {
        $this->data = array();

        return $this;
    }
   
    /**
     * Zet logger-context in optie
     *
     * @param array $context
     * @return void
     */
    public function set_logger_context() {

        $source = sprintf( 'siw-%s-%s', $this->name, date( 'Y-m-d' ) );
        $source = str_replace( ' ', '-', $source );
        $context = array( 'source' => $source );
        update_site_option( $this->logger_context_option, $context );
        
        return $this;
    }

    /**
     * Haal logger-context op
     *
     * @return void
     */
    protected function get_logger_context() {
        $logger_context = get_site_option( $this->logger_context_option );
        return $logger_context;
    }

    /**
     * Verwijder optie met logger context
     *
     * @return void
     */
    protected function delete_logger_context() {
        delete_site_option( $this->logger_context_option );
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $level
     * @param string $message
     * @return void
     */
    protected function log( $level, $message ) {
        $logger = wc_get_logger();
        $context = $this->get_logger_context();
        $logger->log( $level, $message, $context );
    }


    /**
     * Zet het aantal verwerkte items
     *
     * @param int $count
     * @return void
     */
    protected function set_processed_count( $processed_count ) {
        update_site_option( $this->processed_count_option, $processed_count );
        return $this;
    }

    /**
     * Haal aantal verwerkte items op
     *
     * @return int
     */   
    protected function get_processed_count() {
        $processed_count = get_site_option( $this->processed_count_option );
        return $processed_count;
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function reset_processed_count() {
        $this->set_processed_count( 0 );
        return $this;
    }


    /**
     * Hoog aantal verwerkte items met 1 op
     *
     * @return void
     */
    protected function increment_processed_count() {
        $processed_count = $this->get_processed_count();
        $processed_count++;
        $this->set_processed_count( $processed_count );
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function delete_processed_count() {
        delete_site_option( $this->processed_count_option );
        return $this;
    }

    /**
     * Functie om te verwerken data te selecteren.
     *
     * @return array
     */
    protected abstract function select_data();


    /**
     * Undocumented function
     *
     * @return void
     */
    public function start() {
 
        $this->set_logger_context();
        $this->reset_processed_count();
        $this->log( 'info', sprintf( 'Start %s', $this->name ) );

        /* Te verwerken items ophalen */
        $data = $this->select_data();

        /* Afbreken als er geen items te verwerken zijn */
        if ( empty( $data ) ) {
            $this->log('info', sprintf( 'Eind %s, geen items te verwerken', $this->name ) );
            $this->delete_logger_context();
            $this->delete_processed_count();
            return;
        }

        /* Data opdelen in batches */
        $batches = array_chunk( $data, $this->batch_size );
        foreach ( $batches as $batch ) {
            foreach ( $batch as $item ) {
                $this->push_to_queue( $item );
            }
            $this->save()->empty_queue();
        }

        /* Start proces */
        $this->dispatch();
    }
    
    
    /**
     * Undocumented function
     *
     * @return void
     */
	protected function complete() {
        parent::complete();
        $this->log( 'info', sprintf( 'Eind %s, %s items verwerkt', $this->name, $this->get_processed_count() ) );
        $this->delete_logger_context();
        $this->delete_processed_count();
	}

}
