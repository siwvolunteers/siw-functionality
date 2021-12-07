<?php declare(strict_types=1);

namespace SIW\Actions;

/**
 * Scheduler voor cron jobs
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Scheduler {

	/** Group voor starten */
	const START_GROUP = 'siw_start';

	/** Tijdslimiet voor queue runner (default is 30 seconden) */
	const TIME_LIMIT = 2 * MINUTE_IN_SECONDS;

	/** Starttijd van acties */
	const START_TIME_GENERAL = '03:00';

	/** Starttijd van FPL-import */
	const START_TIME_IMPORT_FPL = '02:00';

	/** Starttijdvan project-import */
	const START_TIME_IMPORT_PROJECTS = '01:00';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'siw_update_plugin', [ $self, 'schedule_actions'] );

		add_filter( 'action_scheduler_retention_period', fn(): int => DAY_IN_SECONDS );
		add_filter( 'action_scheduler_queue_runner_time_limit', fn(): int => self::TIME_LIMIT );
	}

	/**  Schedule acties */
	public function schedule_actions() {

		//Huidige start-actions unschedulen
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

		//Nieuw start-actions schedulen
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
		$start_time = match( $id ) {
			'import_plato_projects',
			'import_plato_dutch_projects'      => self::START_TIME_IMPORT_PROJECTS,
			'import_plato_project_free_places' => self::START_TIME_IMPORT_FPL,
			default                            => self::START_TIME_GENERAL,
		};

		return strtotime( 'tomorrow ' . $start_time . wp_timezone_string() );
	}
}
