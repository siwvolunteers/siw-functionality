<?php declare(strict_types=1);

namespace SIW\Data\Plato;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Language_Skill_Level: string implements Labels {

	use Enum_List;

	case BEGINNER = '1';
	case ELEMENTARY = '2';
	case INTERMEDIATE = '3';
	case ADVANCED = '4';

	#[\Override]
	public function label(): string {
		return match ( $this ) {
			self::BEGINNER => __( 'Matig', 'siw' ),
			self::ELEMENTARY => __( 'Redelijk', 'siw' ),
			self::INTERMEDIATE => __( 'Goed', 'siw' ),
			self::ADVANCED => __( 'Uitstekend', 'siw' ),
		};
	}
}
