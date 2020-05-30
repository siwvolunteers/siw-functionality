<?php

namespace SIW\Core;

/**
 * Scheduler voor cron jobs
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo      Import van Nederlandse projecten
 */
class Scheduler {

	/**
	 * Tijdstip achtergrondprogramma's
	 *
	 * @var string
	 */
	const TS_SCHEDULED_JOBS = '03:00';

	/**
	 * Tijdstip bijwerken groepsprojecten
	 *
	 * @var string
	 */
	const TS_IMPORT_PROJECTS = '1:00';

	/**
	 * Tijdstip bijwerken vrije plaatsen
	 *
	 * @var string
	 */
	const TS_UPDATE_FREE_PLACES = '2:00';

	/**
	 * Optienaam
	 * 
	 * @var string
	 */
	const OPTION_NAME = 'siw_scheduled_cron_jobs';

	/**
	 * Interval tussen jobs in minuten
	 * 
	 * @var int
	 */
	const CRON_JOB_INTERVAL = 5;

	/**
	 * Jobs
	 *
	 * @var array
	 */
	protected static $jobs = [];


	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'siw_update_plugin', [ $self, 'schedule_events'] );
	}

	/**
	 * Plant jobs en andere processen in
	 */
	public function schedule_events() {
		$this->unschedule_jobs();
		$this->schedule_jobs();
		$this->schedule_update_free_places();
		$this->schedule_update_projects();
	}

	/**
	 * Plant jobs in
	 */
	protected function schedule_jobs() {
		$timestamp = strtotime( 'tomorrow ' . self::TS_SCHEDULED_JOBS . wp_timezone_string() );
		foreach ( self::$jobs as $index => $job ) {
			wp_schedule_event( $timestamp + ( $index * self::CRON_JOB_INTERVAL * MINUTE_IN_SECONDS ), 'daily', $job );
		}
		$this->set_scheduled_jobs( self::$jobs );
	}

	/**
	 * Plant updaten van vrije plaatsen in
	 */
	protected function schedule_update_free_places() {
		$new_timestamp = strtotime( 'tomorrow ' . self::TS_UPDATE_FREE_PLACES . wp_timezone_string() );

		if ( wp_next_scheduled( 'siw_update_free_places' ) ) {
			wp_clear_scheduled_hook( 'siw_update_free_places' );
		}

		wp_schedule_event( $new_timestamp, 'daily', 'siw_update_free_places' );
	}

	/**
	 * Plant update van groepsprojecten in
	 */
	protected function schedule_update_projects() {
		$new_timestamp = strtotime( 'tomorrow ' . self::TS_IMPORT_PROJECTS . wp_timezone_string() );
		if ( wp_next_scheduled( 'siw_import_workcamps' ) ) {
			wp_clear_scheduled_hook( 'siw_import_workcamps' );
		}
		wp_schedule_event( $new_timestamp, 'daily', 'siw_import_workcamps' );
	}

	/**
	 * Voegt taak toe
	 *
	 * @param string $hook
	 */
	public static function add_job( string $hook ) {
		self::$jobs[] = $hook;
	}

	/**
	 * Verwijdert ingeplande taken
	 */
	protected function unschedule_jobs() {
		$scheduled_jobs = $this->get_scheduled_jobs();
		foreach ( $scheduled_jobs as $job ) {
			if ( wp_next_scheduled( $job ) ) {
				wp_clear_scheduled_hook( $job );
			}
		}
	}

	/**
	 * Haalt ingeplande taken op uit database
	 * 
	 * @return array
	 */
	protected function get_scheduled_jobs() : array {
		return (array) get_option( self::OPTION_NAME );
	}

	/**
	 * Slaat ingeplande taken op in database
	 *
	 * @param array $jobs
	 */
	protected function set_scheduled_jobs( array $jobs = [] ) {
		update_option( self::OPTION_NAME, $jobs, false );
	}
}
