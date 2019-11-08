<?php

/**
 * Uitbreiding van WP_Background_Process
 * 
 * - Logging
 * - Aantal verwerkte items bijhouden
 * - Toevoegen aan admin bar
 * - Schedulen
 * 
 * @package   SIW\Batch
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
abstract class SIW_Batch_Job extends \WP_Background_Process {
	
	/**
	 * Prefix
	 * 
	 * @var string
	 */
	protected $prefix = 'siw';

	/**
	 * Optie voor logger-context
	 *
	 * @var string
	 */
	protected $logger_context_option;

	/**
	 * Optie voor teller verwerkte items
	 *
	 * @var string
	 */
	protected $processed_count_option;

	/**
	 * Naam van proces (voor logging en admin bar)
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Category (voor admin bar)
	 *
	 * @var string
	 */
	protected $category;

	/**
	 * Grootte van batch
	 * 
	 * @var integer
	 */
	protected $batch_size = 500;

	/**
	 * Geeft aan of deze batch job ingepland moet worden
	 *
	 * @var bool
	 */
	protected $schedule_job = true;

	/**
	 * Initiate new background process
	 */
	public function __construct() {
		parent::__construct();
		$this->logger_context_option = $this->identifier . '_logger_context';
		$this->processed_count_option = $this->identifier . '_processed_count';
	}

	/**
	 * Init
	 */
	public static function init() {
		$self = new static();
		add_action( "siw_{$self->action}", [ $self, 'start'] );

		\SIW\Admin\Admin_Bar::add_node(
			sanitize_title( $self->category ),
			[
				'title' => ucfirst( $self->category ) ]
		);

		\SIW\Admin\Admin_Bar::add_action(
			$self->action,
			[ 
				'parent' => sanitize_title( $self->category ),
				'title'  => ucfirst( $self->name ),
			]
		);
		
		if ( true == $self->schedule_job ) {
			\SIW\Scheduler::add_job( "siw_{$self->action}" );
		}
	}

	/**
	 * Leeg de queue
	 */
	protected function empty_queue() {
		$this->data = [];
	}

	/**
	 * Zet logger-context in optie
	 *
	 * @param array $context
	 */
	public function set_logger_context() {
		$source = sanitize_title( "siw-{$this->name}" );
		$context = [ 'source' => $source ];
		update_site_option( $this->logger_context_option, $context );
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
	 * Verwijder opties
	 */
	protected function cleanup() {
		delete_site_option( $this->logger_context_option );
		delete_site_option( $this->processed_count_option );
	}

	/**
	 * Schrijf boodschap naar log
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
	 */
	protected function set_processed_count( $processed_count ) {
		update_site_option( $this->processed_count_option, $processed_count );
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
	 * Hoog aantal verwerkte items met 1 op
	 */
	protected function increment_processed_count() {
		$processed_count = $this->get_processed_count();
		$processed_count++;
		$this->set_processed_count( $processed_count );
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
	 */
	public function start() {
 
		$this->set_logger_context();
		$this->set_processed_count( 0 );
		$this->log( 'info', sprintf( 'Start %s', $this->name ) );

		/* Te verwerken items ophalen */
		$data = $this->select_data();

		/* Afbreken als er geen items te verwerken zijn */
		if ( empty( $data ) ) {
			$this->log('info', sprintf( 'Eind %s, geen items te verwerken', $this->name ) );
			$this->cleanup();
			return;
		}

		/* Data opdelen in batches */
		$batches = array_chunk( $data, $this->batch_size );
		foreach ( $batches as $batch ) {
			foreach ( $batch as $item ) {
				$this->push_to_queue( $item );
			}
			$this->save();
			$this->empty_queue();
		}

		/* Start proces */
		$this->dispatch();
	}

	/**
	 * Afronden
	 */
	protected function complete() {
		parent::complete();
		$this->log( 'info', sprintf( 'Eind %s, %s items verwerkt', $this->name, $this->get_processed_count() ) );
		$this->cleanup();
	}

}