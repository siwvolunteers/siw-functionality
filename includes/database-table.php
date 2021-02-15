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
	protected static function values(): \Closure {
		return function( string $value ): string {
			return strtolower( $value );
		};
	}
}
