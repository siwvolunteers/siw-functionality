<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

/**
 * Soort vacature
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Job_Type: string implements Labels {
	case VOLUNTEER = 'volunteer';
	case PAID = 'paid';
	case INTERNSHIP = 'internship';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::VOLUNTEER  => __( 'Vrijwillige functie', 'siw' ),
			self::PAID       => __( 'Betaalde functie', 'siw' ),
			self::INTERNSHIP =>__( 'Stage', 'siw' ),
		};
	}
}
