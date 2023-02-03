<?php declare(strict_types=1);

namespace SIW\Data;

use \Spatie\Enum\Enum;

/**
 * Special pagina's waarnaar verwezen kan worden
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self CONTACT()
 * @method static self CHILD_POLICY()
 * @method static self NEWSLETTER_CONFIRMATION()
 */
class Special_Page extends Enum {

	/** {@inheritDoc} */
	protected static function values(): \Closure {
		return function( string $value ): string {
			return strtolower( $value );
		};
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'CONTACT'                 => __( 'Contact', 'siw' ),
			'CHILD_POLICY'            => __( 'Kinderbeleid', 'siw' ),
			'NEWSLETTER_CONFIRMATION' => __( 'Bevestiging nieuwsbrief', 'siw' ),
		];
	}
}
