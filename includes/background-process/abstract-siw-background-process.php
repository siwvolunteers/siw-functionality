<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Uitbreiding van WP_Background_Process
 * 
 * - Logging
 * - Aantal verwerkte items bijhouden
 * 
 * @package SIW\Background-Process
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
abstract class SIW_Background_Process extends WP_Background_Process {
	
	/**
	 * @var string
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
		$this->data = [];

		return $this;
	}

	/**
	 * Zet logger-context in optie
	 *
	 * @param array $context
	 * @return $this
	 */
	public function set_logger_context() {
		$source = sanitize_title( "siw-{$this->name}" );
		$context = [ 'source' => $source ];
		update_site_option( $this->logger_context_option, $context );
		
		return $this;
	}

	/**
	 * Haal logger-context op
	 *
	 * @return array
	 */
	protected function get_logger_context() {
		$logger_context = get_site_option( $this->logger_context_option );
		return $logger_context;
	}

	/**
	 * Verwijder optie met logger context
	 *
	 * @return $this
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
	 */
	protected function log( $level, $message ) {
		$logger = wc_get_logger();
		$context = $this->get_logger_context();
		$logger->log( $level, $message, $context );
	}


	/**
	 * Zet het aantal verwerkte items
	 *
	 * @param int $processed_count
	 * @return $this
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
	 * Zet het aantal verwerkte items op 0
	 *
	 * @return $this
	 */
	public function reset_processed_count() {
		$this->set_processed_count( 0 );
		return $this;
	}

	/**
	 * Hoog aantal verwerkte items met 1 op
	 *
	 * @return $this
	 */
	protected function increment_processed_count() {
		$processed_count = $this->get_processed_count();
		$processed_count++;
		$this->set_processed_count( $processed_count );
		return $this;
	}

	/**
	 * Verwijder het aantal verwerkte items
	 *
	 * @return $this
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
	 * Starten achtergrondproces
	 *
	 * - Logger
	 * - Aantal verwerkte items
	 * - Gegevens ophalen
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
