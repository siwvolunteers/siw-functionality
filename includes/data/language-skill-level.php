<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

/**
 * Niveau van taalvaardigheid
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Language_Skill_Level:string implements Labels {
	case MEDIOCRE = '1';
	case REASONABLE = '2';
	case GOOD = '3';
	case EXCELLENT = '4';

	/** {@inheritDoc} */
	public function label(): string {
		return match ($this) {
			self::MEDIOCRE => __( 'Matig', 'siw' ),
			self::REASONABLE => __( 'Redelijk', 'siw' ),
			self::GOOD => __( 'Goed', 'siw' ),
			self::EXCELLENT => __( 'Uitstekend', 'siw' ),
		};
	}
}
