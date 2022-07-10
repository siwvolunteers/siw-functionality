<?php declare(strict_types=1);

namespace SIW\Interfaces\Actions;

/**
 * Interface voor acties
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Action {

	/** Geeft id van action terug */
	public function get_id() : string;

	/** Geeft naam van action terug */
	public function get_name() : string;

}
