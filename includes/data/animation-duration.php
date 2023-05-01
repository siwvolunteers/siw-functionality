<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

/**
 * Animatie-duur
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Animation_Duration:int implements Labels {

	case DURATION_200_MS = 200;
	case DURATION_250_MS = 250;
	case DURATION_300_MS = 300;
	case DURATION_350_MS = 350;
	case DURATION_400_MS = 400;
	case DURATION_450_MS = 450;
	case DURATION_500_MS = 500;
	case DURATION_550_MS = 550;
	case DURATION_600_MS = 600;
	case DURATION_650_MS = 650;
	case DURATION_700_MS = 700;
	case DURATION_760_MS = 750;
	case DURATION_800_MS = 800;
	case DURATION_850_MS = 850;
	case DURATION_900_MS = 900;
	case DURATION_950_MS = 950;
	case DURATION_1000_MS = 1000;
	case DURATION_1050_MS = 1050;
	case DURATION_1100_MS = 1100;
	case DURATION_1150_MS = 1150;
	case DURATION_1200_MS = 1200;
	case DURATION_1250_MS = 1250;
	case DURATION_1300_MS = 1300;
	case DURATION_1350_MS = 1350;
	case DURATION_1400_MS = 1400;
	case DURATION_1450_MS = 1450;
	case DURATION_1500_MS = 1500;
	case DURATION_1550_MS = 1550;
	case DURATION_1600_MS = 1600;
	case DURATION_1650_MS = 1650;
	case DURATION_1700_MS = 1700;
	case DURATION_1760_MS = 1750;
	case DURATION_1800_MS = 1800;
	case DURATION_1850_MS = 1850;
	case DURATION_1900_MS = 1900;
	case DURATION_1950_MS = 1950;
	case DURATION_2000_MS = 2000;

	/** {@inheritDoc} */
	public function label(): string {
		return sprintf( '%d ms', $this->value );
	}
}
