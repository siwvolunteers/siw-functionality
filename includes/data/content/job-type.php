<?php declare(strict_types=1);

namespace SIW\Data\Content;

use \Spatie\Enum\Enum;

/**
 * Type vacatures
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self VOLUNTEER() Vrijwillig
 * @method static self PAID() Betaald
 * @method static self INTERNSHIP() Stage
 */
class Job_Type extends Enum {

	/** {@inheritDoc} */
	protected static function values(): \Closure {
		return function( string $value ): string {
			return strtolower( $value );
		};
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'VOLUNTEER'  => __( 'Vrijwillig', 'siw' ),
			'PAID'       => __( 'Betaald', 'siw' ),
			'INTERNSHIP' => __( 'Stage', 'siw' ),
		];
	}
}
