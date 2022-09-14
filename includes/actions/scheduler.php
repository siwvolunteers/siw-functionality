<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Update;

/**
 * Scheduler voor cron jobs
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Scheduler {

	/** Group voor starten */
	const START_GROUP = 'siw_start';

	/** Retentie-periode voor uitgevoerde acties */
	const RETENTION_PERIOD = MINUTE_IN_SECONDS;

	/** Aantal concurrent batches voor AS */
	const CONCURRENT_BATCHES = 2;

	/** Tijdslimiet voor queue runner (default is 30 seconden) */
	const TIME_LIMIT = MINUTE_IN_SECONDS;

	/** Starttijd van acties */
	const START_TIME_GENERAL = '03:00';

	/** Starttijd van FPL-import */
	const START_TIME_IMPORT_FPL = '02:00';

	/** Starttijdvan project-import */
	const START_TIME_IMPORT_PROJECTS = '01:00';

	/** Capabilities die nodig zijn om de acties uit te kunnen voeren */
	const TEMPORARY_USER_CAPABILITIES = [
		'delete_posts',
	];

	/** Init */
	public static function init() {
		$self = new self();
		add_action( Update::PLUGIN_UPDATED_HOOK, [ $self, 'schedule_actions' ] );

		add_filter( 'action_scheduler_retention_period', fn(): int => self::RETENTION_PERIOD );
		add_filter( 'action_scheduler_queue_runner_time_limit', fn(): int => self::TIME_LIMIT );
		add_filter( 'action_scheduler_queue_runner_concurrent_batches', fn(): int => self::CONCURRENT_BATCHES );

		add_action( 'action_scheduler_before_process_queue', [ $self, 'add_temporary_user_capabilities' ] );
		add_action( 'action_scheduler_after_process_queue', [ $self, 'remove_temporary_user_capabilities' ] );
	}

	/** Voegt tijdelijke user capabilities toe */
	public function add_temporary_user_capabilities() {

		if ( is_user_logged_in() ) {
			return;
		}

		foreach ( self::TEMPORARY_USER_CAPABILITIES as $capability ) {
			wp_get_current_user()->add_cap( $capability );
		}
	}

	/** Verwijdert tijdelijke user capabilities toe */
	public function remove_temporary_user_capabilities() {

		if ( is_user_logged_in() ) {
			return;
		}

		foreach ( self::TEMPORARY_USER_CAPABILITIES as $capability ) {
			wp_get_current_user()->remove_cap( $capability );
		}
	}

	/**  Schedule acties */
	public function schedule_actions() {

		// Huidige start-actions unschedulen
		$scheduled_actions = as_get_scheduled_actions(
			[
				'group'    => self::START_GROUP,
				'status'   => \ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
			]
		);
		array_walk(
			$scheduled_actions,
			fn( \ActionScheduler_Action $action ) => as_unschedule_all_actions( $action->get_hook() )
		);

		// Nieuw start-actions schedulen
		$actions = apply_filters( 'siw_scheduler_actions', [] );

		array_walk(
			$actions,
			fn( string $action_id ) => as_schedule_recurring_action(
				$this->determine_start_time( $action_id ),
				DAY_IN_SECONDS,
				"siw_action_{$action_id}_start",
				[],
				self::START_GROUP
			)
		);
	}

	/** Bepaal starttijd obv ID */
	protected function determine_start_time( string $id ): int {

		$start_time = match ( $id ) {
			'import_plato_projects',
			'import_plato_dutch_projects'      => self::START_TIME_IMPORT_PROJECTS,
			'import_plato_project_free_places' => self::START_TIME_IMPORT_FPL,
			default                            => self::START_TIME_GENERAL,
		};
		return strtotime( 'tomorrow ' . $start_time . wp_timezone_string() );
	}
}
