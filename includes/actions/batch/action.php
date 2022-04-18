<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\Update;

/**
 * Class om actie toe te voegen
 *
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Action {

	/** Group voor verwerken */
	const PROCESS_GROUP = 'siw_process';

	/** Group voor update */
	const UPDATE_GROUP = 'siw_update';

	/** Actie */
	protected Batch_Action_Interface $action;

	/** Init */
	public function __construct( Batch_Action_Interface $action ) {
		$this->action = $action;

		if ( $this->action->must_be_scheduled() ) {
			add_filter( 'siw_scheduler_actions', [ $this, 'add_action_to_scheduler'] );
		}

		if ( $this->action->must_be_run_on_update() ) {
			add_action( Update::PLUGIN_UPDATED_HOOK, [ $this, 'add_action_to_update'] );
		}

		add_filter( 'woocommerce_debug_tools', [ $this, 'add_action_to_wc_debug_tools'] );

		add_action( "siw_action_{$this->action->get_id()}_start", [ $this, 'start']);
		add_action( "siw_action_{$this->action->get_id()}_process", [ $this->action, 'process']);
	}

	/** Voegt actie aan scheduler toe */
	public function add_action_to_scheduler( array $actions ) : array {
		$actions[] = $this->action->get_id();
		return $actions;
	}

	/** Start actie bij update van plugin */
	public function add_action_to_update() {
		as_schedule_single_action(
			current_time( 'timestamp', true ),
			"siw_action_{$this->action->get_id()}_start",
			[],
			self::UPDATE_GROUP
		);
	}

	/** Voegt actie toe aan WooCommerce debug tools (om handmatig te starten) */
	public function add_action_to_wc_debug_tools( array $tools ): array {
		$tools[ "siw_{$this->action->get_id()}" ] = [
			'name'     => "SIW: {$this->action->get_name()}",
			'button'   => __( 'Starten', 'siw' ),
			'desc'     => '',
			'callback' => [ $this, 'start'],
		];
		return $tools;
	}

	/** Start actie */
	public function start(): string {
		$data = $this->action->select_data();

		array_walk(
			$data,
			fn( $item ) => as_schedule_single_action(
				current_time( 'timestamp', true ),
				"siw_action_{$this->action->get_id()}_process",
				[ 'id' => $item ],
				self::PROCESS_GROUP
			)
		);

		return sprintf( __( 'Actie gestart: %s', 'siw' ), $this->action->get_name() );
	}
}
