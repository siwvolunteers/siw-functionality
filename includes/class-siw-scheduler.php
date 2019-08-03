<?php

/**
 * Scheduler voor cron jobs
 * 
 * @package     SIW
 * @copyright   2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 * 
 * @todo        Import van Nederlandse projecten
 */
class SIW_Scheduler {

	/**
	 * Jobs
	 *
	 * @var array
	 */
	protected static $jobs = [];

	/**
	 * Optienaam
	 * 
	 * @var string
	 */
	protected $option_name = 'siw_scheduled_cron_jobs';

	/**
	 * Interval tussen jobs in minuten
	 * 
	 * @var int
	 */
	const CRON_JOB_INTERVAL = 5;

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
		$timestamp = SIW_Util::convert_timestamp_to_gmt( strtotime( 'tomorrow ' . SIW_Properties::TS_SCHEDULED_JOBS ) );
		foreach ( self::$jobs as $index => $job ) {
			wp_schedule_event( $timestamp + ( $index * self::CRON_JOB_INTERVAL * MINUTE_IN_SECONDS ) , 'daily', $job );
		}
		$this->set_scheduled_jobs( self::$jobs );
	}

	/**
	 * Plant updaten van vrije plaatsen in
	 */
	protected function schedule_update_free_places() {
		$new_timestamp = SIW_Util::convert_timestamp_to_gmt( strtotime( 'tomorrow ' . SIW_Properties::TS_UPDATE_FREE_PLACES ) );
		if ( wp_next_scheduled( 'siw_update_free_places' ) ) {
			$current_timestamp = wp_next_scheduled( 'siw_update_free_places' );
			wp_unschedule_event( $current_timestamp, 'siw_update_free_places' );
		}
		wp_schedule_event( $new_timestamp, 'daily', 'siw_update_free_places' );	
	}

	/**
	 * Plant update van groepsprojecten in
	 */
	protected function schedule_update_projects() {

		$new_timestamp = SIW_Util::convert_timestamp_to_gmt( strtotime( 'tomorrow ' . SIW_Properties::TS_UPDATE_PROJECTS ) );

		if ( wp_next_scheduled( 'siw_update_workcamps' ) ) {
			$current_timestamp = wp_next_scheduled( 'siw_update_workcamps' );
			wp_unschedule_event( $current_timestamp, 'siw_update_workcamps' );
		}
		wp_schedule_event( $new_timestamp, 'daily', 'siw_update_workcamps' );		
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
				$timestamp = wp_next_scheduled( $job );
				wp_unschedule_event( $timestamp, $job );
			}
		}
	}

	/**
	 * Haalt ingeplande taken op uit database
	 * 
	 * @return array
	 */
	protected function get_scheduled_jobs() {
		$jobs = (array) get_option( $this->option_name );
		return $jobs;
	}

	/**
	 * Slaat ingeplande taken op in database
	 *
	 * @param array $jobs
	 */
	protected function set_scheduled_jobs( array $jobs = [] ) {
		update_option( $this->option_name, $jobs, false );
	}
}
