<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;

/**
 * Aanpassingen voor GeneratePress
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * @see       https://actionscheduler.org/
 */
class Action_Scheduler extends Base {

	#[Add_Filter( 'action_scheduler_retention_period' )]
	/** Retentie-periode voor uitgevoerde acties */
	private const RETENTION_PERIOD = DAY_IN_SECONDS;

	#[Add_Filter( 'action_scheduler_queue_runner_concurrent_batches' )]
	/** Aantal concurrent batches voor AS */
	private const CONCURRENT_BATCHES = 2;

	#[Add_Filter( 'action_scheduler_queue_runner_time_limit' )]
	/** Tijdslimiet voor queue runner (default is 30 seconden) */
	private const TIME_LIMIT = MINUTE_IN_SECONDS;

	/** Capabilities die nodig zijn om de acties uit te kunnen voeren */
	private const TEMPORARY_USER_CAPABILITIES = [
		'delete_posts',
	];

	#[Add_Action( 'action_scheduler_before_process_queue' )]
	/** Voegt tijdelijke user capabilities toe */
	public function add_temporary_user_capabilities() {
		if ( is_user_logged_in() ) {
			return;
		}
		foreach ( self::TEMPORARY_USER_CAPABILITIES as $capability ) {
			wp_get_current_user()->add_cap( $capability );
		}
	}

	#[Add_Action( 'action_scheduler_after_process_queue' )]
	/** Verwijdert tijdelijke user capabilities toe */
	public function remove_temporary_user_capabilities() {
		if ( is_user_logged_in() ) {
			return;
		}
		foreach ( self::TEMPORARY_USER_CAPABILITIES as $capability ) {
			wp_get_current_user()->remove_cap( $capability );
		}
	}
}
