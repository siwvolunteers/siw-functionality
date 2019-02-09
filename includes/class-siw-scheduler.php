<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Scheduler voor cron jobs
 * 
 * @package     SIW
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 * 
 * @todo        schedulen plato imports naar aparte class
 */

class SIW_Scheduler {

	/**
	 * Optienaam
	 * 
	 * @var string
	 */
	protected $option_name = 'siw_scheduled_cron_jobs';

	/**
	 * Undocumented variable
	 *
	 * @var int
	 */
	protected $ts_scheduled_jobs;

	/**
	 * Undocumented variable
	 *
	 * @var int
	 */
	protected $ts_update_projects;

	/**
	 * Undocumented variable
	 *
	 * @var int
	 */	
	protected $ts_update_free_places;

	/**
	 * Interval tussen jobs in minuten
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
	 * Undocumented function
	 */
	public function schedule_events() {
		$this->unschedule_jobs();
		$this->set_timestamps();
		$this->schedule_jobs();
		$this->schedule_update_free_places();
		//$this->schedule_update_projects();
	}

	/**
	 * Undocumented function
	 */
	protected function set_timestamps() {
		$this->ts_scheduled_jobs = SIW_Util::convert_timestamp_to_gmt( strtotime( 'tomorrow ' . SIW_Properties::TS_SCHEDULED_JOBS ) );
		$this->ts_update_free_places = SIW_Util::convert_timestamp_to_gmt( strtotime( 'tomorrow ' . SIW_Properties::TS_UPDATE_FREE_PLACES ) );
		$this->ts_update_projects = SIW_Util::convert_timestamp_to_gmt( strtotime( 'tomorrow ' . SIW_Properties::TS_UPDATE_PROJECTS ) );
	}

	/**
	 * Undocumented function
	 */
	protected function schedule_jobs() {
		$jobs = $this->get_jobs();

		foreach ( $jobs as $index => $job ) {
			wp_schedule_event( $this->ts_scheduled_jobs + ( $index * self::CRON_JOB_INTERVAL * MINUTE_IN_SECONDS ) , 'daily', $job );
		}

		$this->set_scheduled_jobs( $jobs );
	}

	/**
	 * Undocumented function
	 */
	protected function schedule_update_free_places() {
		if ( wp_next_scheduled( 'siw_update_free_places' ) ) {
			$timestamp = wp_next_scheduled( 'siw_update_free_places' );
			wp_unschedule_event( $timestamp, 'siw_update_free_places' );
		}
		wp_schedule_event( $this->ts_update_free_places, 'daily', 'siw_update_free_places' );	
	}

	/**
	 * Undocumented function
	 */
	protected function schedule_update_projects() {
		if ( wp_next_scheduled( 'siw_update_projects' ) ) {
			$timestamp = wp_next_scheduled( 'siw_update_projects' );
			wp_unschedule_event( $timestamp, 'siw_update_projects' );
		}
		wp_schedule_event( $this->ts_update_projects, 'daily', 'siw_update_projects' );		
	}

	/**
	 * Undocumented function
	 *
	 * @param string $action
	 */
	public static function add_job( $action ) {
		add_filter( 'siw_cron_jobs', function( $actions ) use( $action ) {
			$actions[] = $action;
			return $actions;
		});
	}

	/**
	 * Undocumented function
	 * 
	 * @return array
	 */
	protected function get_jobs() {
		$jobs = [];
		/**
		 * Cron jobs
		 * 
		 * @param array $jobs
		 */
		$jobs = apply_filters( 'siw_cron_jobs', $jobs );
		return $jobs;
	}

	/**
	 * Undocumented function
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
	 * Undocumented function
	 * 
	 * @return array
	 */
	protected function get_scheduled_jobs() {
		$jobs = (array) get_option( $this->option_name );
		return $jobs;
	}

	/**
	 * Undocumented function
	 *
	 * @param array $jobs
	 */
	protected function set_scheduled_jobs( $jobs = [] ) {
		update_option( $this->option_name, $jobs, false );
	}
}
