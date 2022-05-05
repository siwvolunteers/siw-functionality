<?php declare(strict_types=1);

namespace SIW;

use \Spatie\Enum\Enum;

/**
 * Database-tabellen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self PLATO_PROJECTS()
 * @method static self PLATO_PROJECT_FREE_PLACES()
 * @method static self PLATO_PROJECT_IMAGES()
 */
class Database_Table extends Enum {

	/** {@inheritDoc} */
	protected static function values(): \Closure {
		return function( string $value ): string {
			return strtolower( $value );
		};
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'PLATO_PROJECTS'            => 'Plato projecten',
			'PLATO_PROJECT_FREE_PLACES' => 'Plato project vrije plaatsen',
			'PLATO_PROJECT_IMAGES'      => 'Plato projectafbeeldingen',
		];
	}
}
