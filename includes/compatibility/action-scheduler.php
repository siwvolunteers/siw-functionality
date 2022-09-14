<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor GeneratePress
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * @see       https://actionscheduler.org/
 */
class Action_Scheduler {

	/** Retentie-periode voor uitgevoerde acties */
	const RETENTION_PERIOD = MINUTE_IN_SECONDS;

	/** Aantal concurrent batches voor AS */
	const CONCURRENT_BATCHES = 2;

	/** Tijdslimiet voor queue runner (default is 30 seconden) */
	const TIME_LIMIT = MINUTE_IN_SECONDS;

	/** Capabilities die nodig zijn om de acties uit te kunnen voeren */
	const TEMPORARY_USER_CAPABILITIES = [
		'delete_posts',
	];

	/** Init */
	public static function init() {
		$self = new self();
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
}
