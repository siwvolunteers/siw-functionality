<?php declare(strict_types=1);

namespace SIW\Structured_Data;

use \Spatie\Enum\Enum;

/**
 * Enum voor structured data
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * @see https://schema.org/Enumeration
 */
abstract class Enumeration extends Enum {

	/** {@inheritDoc} */
	protected static function values() : \Closure {
		return function( string $value ): string {
			return "https://schema.org/{$value}";
		};
	}
}
