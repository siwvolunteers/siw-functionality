<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Colors;
use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;
use SIW\Util\CSS;

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
			self::AFRICA        => CSS::RED_COLOR,
			self::ASIA          => CSS::GREEN_COLOR,
			self::EUROPE        => CSS::BLUE_COLOR,
			self::NORTH_AMERICA => CSS::YELLOW_COLOR,
			self::LATIN_AMERICA => CSS::PURPLE_COLOR,
		};
	}
}
