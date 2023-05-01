<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\EnuMS\Labels;

/**
 * Animatie-vertragin
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Animation_Delay:int implements Labels {

	case NONE = 0;
	case DELAY_100_MS = 100;
	case DELAY_150_MS = 150;
	case DELAY_200_MS = 200;
	case DELAY_250_MS = 250;
	case DELAY_300_MS = 300;
	case DELAY_350_MS = 350;
	case DELAY_400_MS = 400;
	case DELAY_450_MS = 450;
	case DELAY_500_MS = 500;
	case DELAY_550_MS = 550;
	case DELAY_600_MS = 600;
	case DELAY_650_MS = 650;
	case DELAY_700_MS = 700;
	case DELAY_760_MS = 750;
	case DELAY_800_MS = 800;
	case DELAY_850_MS = 850;
	case DELAY_900_MS = 900;
	case DELAY_950_MS = 950;
	case DELAY_1000_MS = 1000;

	/** {@inheritDoc} */
	public function label(): string {
		return match ($this) {
			self::NONE => __( 'Geen', 'siw' ),
			default => sprintf( '%d ms', $this->value )
		};
	}
}
