<?php declare(strict_types=1);

namespace SIW\Actions;

use ActionScheduler_Action;
use ActionScheduler_Store;

/**
 * Scheduler voor cron jobs
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Scheduler {

	/** Group voor starten */
	const ACTION_GROUP = 'siw_start';

	/** Tijdslimiet voor queue runner (default is 30 seconden) */
	const TIME_LIMIT = 2 * MINUTE_IN_SECONDS;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'siw_update_plugin', [ $self, 'schedule_actions'] );

		add_filter( 'action_scheduler_retention_period', fn() : int => DAY_IN_SECONDS );
		add_filter( 'action_scheduler_queue_runner_time_limit', fn() : int => self::TIME_LIMIT );
	}

	/**  Schedule acties */
	public function schedule_actions() {

		//Huidige start-actions unschedulen
		$scheduled_actions = as_get_scheduled_actions(
			[
				'group'    => self::ACTION_GROUP,
				'status'   => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
			]
		);
		array_walk(
			$scheduled_actions,
			fn( ActionScheduler_Action $action ) => as_unschedule_all_actions( $action->get_hook() )
		);

		//Nieuw start-actions schedulen
		$actions = apply_filters( 'siw_scheduler_actions', [] );

		array_walk(
			$actions,
			fn( string $start_time, string $action_id ) => as_schedule_recurring_action(
				strtotime( 'tomorrow ' . $start_time . wp_timezone_string() ),
				DAY_IN_SECONDS,
				"siw_action_{$action_id}_start",
				[],
				self::ACTION_GROUP
			)
		);
	}
}