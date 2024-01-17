<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;

/**
 * @see       https://actionscheduler.org/
 */
class Action_Scheduler extends Base {

	#[Add_Filter( 'action_scheduler_retention_period' )]
	private const RETENTION_PERIOD = DAY_IN_SECONDS;

	#[Add_Filter( 'action_scheduler_queue_runner_concurrent_batches' )]
	private const CONCURRENT_BATCHES = 2;

	#[Add_Filter( 'action_scheduler_queue_runner_time_limit' )]
	private const TIME_LIMIT = MINUTE_IN_SECONDS;

	private const TEMPORARY_USER_CAPABILITIES = [
		'delete_posts',
	];

	#[Add_Action( 'action_scheduler_before_process_queue' )]
	public function add_temporary_user_capabilities() {
		if ( is_user_logged_in() ) {
			return;
		}
		foreach ( self::TEMPORARY_USER_CAPABILITIES as $capability ) {
			wp_get_current_user()->add_cap( $capability );
		}
	}

	#[Add_Action( 'action_scheduler_after_process_queue' )]
	public function remove_temporary_user_capabilities() {
		if ( is_user_logged_in() ) {
			return;
		}
		foreach ( self::TEMPORARY_USER_CAPABILITIES as $capability ) {
			wp_get_current_user()->remove_cap( $capability );
		}
	}
}
