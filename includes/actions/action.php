<?php declare(strict_types=1);

namespace SIW\Actions;

use SIW\Interfaces\Actions\Action as Action_Interface;

/**
 * Class om actie toe te voegen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.2.0
 */
class Action {

	/** Starttijd van acties */
	const START_TIME_GENERAL = '03:00';

	/** Starttijd van FPL-import */
	const START_TIME_IMPORT_FPL = '02:00';

	/** Starttijdvan project-import */
	const START_TIME_IMPORT_PROJECTS = '01:00';

	/** Group voor verwerken */
	const ACTION_GROUP = 'siw_process';

	/** Actie */
	protected Action_Interface $action;

	/** Init */
	public function __construct( Action_Interface $action ) {
		$this->action = $action;

		add_filter( 'siw_scheduler_actions', [ $this, 'add_action_to_scheduler'] );
		add_filter( 'woocommerce_debug_tools', [ $this, 'add_action_to_wc_debug_tools'] );

		add_action( "siw_action_{$this->action->get_id()}_start", [ $this, 'start']);
		add_action( "siw_action_{$this->action->get_id()}_process", [ $this->action, 'process']);
	}

	/** Voegt actie aan scheduler toe */
	public function add_action_to_scheduler( array $actions ) : array {
		$actions[ $this->action->get_id() ] = $this->determine_start_time( $this->action->get_id() );
		return $actions;
	}

	/** Bepaal starttijd obv ID */
	public function determine_start_time( string $id ) : string {
		switch ( $id ) {
			case 'import_plato_projects':
				$start_time = self::START_TIME_IMPORT_PROJECTS;
				break;
			case 'import_plato_fpl':
				$start_time = self::START_TIME_IMPORT_FPL;
				break;
			default:
				$start_time = self::START_TIME_GENERAL;
		}
		return $start_time;
	}

	/** Voegt actie toe aan WooCommerce debug tools (om handmatig te starten) */
	public function add_action_to_wc_debug_tools( array $tools ) : array {
		$tools[ "siw_{$this->action->get_id()}" ] = [
			'name'     => "SIW: {$this->action->get_name()}",
			'button'   => __( 'Starten', 'siw' ),
			'desc'     => '',
			'callback' => [ $this, 'start'],
		];
		return $tools;
	}

	/** Start actie */
	public function start() : string {
		$data = $this->action->select_data();

		array_walk(
			$data,
			fn( $item ) => as_schedule_single_action(
				current_time( 'timestamp', true ),
				"siw_action_{$this->action->get_id()}_process",
				[ 'id' => $item ], self::ACTION_GROUP
			)
		);
		
		return sprintf( __( 'Actie gestart: %s', 'siw' ), $this->action->get_name() );
	}
}
