<?php declare(strict_types=1);

namespace SIW\Data;

use Spatie\Enum\Enum;

/**
 * Project types
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self ESC()
 * @method static self WORKCAMPS()
 * @method static self SCHOOL_PROJECTS()
 * @method static self WORLD_BASIC()
 */
class Project_Type extends Enum {

	/** {@inheritDoc} */
	protected static function values(): \Closure {
		return function ( string $value ): string {
			return strtolower( $value );
		};
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'WORKCAMPS'       => __( 'Groepsvrijwilligerswerk', 'siw' ),
			'ESC'             => __( 'ESC (European Solidarity Corps)', 'siw' ),
			'SCHOOL_PROJECTS' => __( 'Scholenprojecten', 'siw' ),
			'WORLD_BASIC'     => __( 'Wereld basis', 'siw' ),
		];
	}
}
