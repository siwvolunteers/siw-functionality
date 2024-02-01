<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Colors;
use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Continent: string implements Labels, Colors {
	use Enum_List;

	case AFRICA = 'afrika';
	case ASIA = 'azie';
	case EUROPE = 'europa';
	case NORTH_AMERICA = 'noord_amerika';
	case LATIN_AMERICA = 'latijns_amerika';


	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::AFRICA => __( 'Afrika', 'siw' ),
			self::ASIA => __( 'Azië', 'siw' ),
			self::EUROPE => __( 'Europa', 'siw' ),
			self::NORTH_AMERICA =>  __( 'Noord-Amerika', 'siw' ),
			self::LATIN_AMERICA => __( 'Latijns-Amerika', 'siw' ),
		};
	}

	/** {@inheritDoc} */
	public function color(): string {
		return match ( $this ) {
			self::AFRICA        => Color::RED->color(),
			self::ASIA          => Color::GREEN->color(),
			self::EUROPE        => Color::BLUE->color(),
			self::NORTH_AMERICA => Color::YELLOW->color(),
			self::LATIN_AMERICA => Color::PURPLE->color(),
		};
	}
}
