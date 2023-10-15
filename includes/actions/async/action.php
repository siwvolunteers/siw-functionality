<?php declare(strict_types=1);

namespace SIW\Actions\Async;

use SIW\Interfaces\Actions\Async as Async_Action_Interface;

/**
 * Class om async actie toe te voegen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Action {

	/** Group */
	private const ACTION_GROUP = 'siw_async';

	/** Init */
	public function __construct( protected Async_Action_Interface $action ) {
		add_action( "siw_async_action_{$this->action->get_id()}_enqueue", [ $this, 'enqueue' ] );
		add_action( "siw_async_action_{$this->action->get_id()}_process", [ $this->action, 'process' ], 10, $this->action->get_argument_count() );
	}

	/** Zet async actie in de wachtrij */
	public function enqueue( array $data ) {
		as_enqueue_async_action( "siw_async_action_{$this->action->get_id()}_process", $data, self::ACTION_GROUP );
	}
}
