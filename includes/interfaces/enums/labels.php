<?php declare(strict_types=1);

namespace SIW\Interfaces\Enums;

/**
 * Interface voor Enums met labels
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
interface Labels {
	/** Geeft label van enum terug */
	public function label(): string;
}
