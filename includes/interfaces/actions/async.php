<?php declare(strict_types=1);

namespace SIW\Interfaces\Actions;

/**
 * Interface voor async acties
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Async extends Action {

	/** Verwerk item */
	public function process();

	/** Geeft het aantal argument terug */
	public function get_argument_count() : int;
}
