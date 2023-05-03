<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

/**
 * Nederlandse provincie
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
enum Dutch_Province:string implements Labels {
	case NB = 'nb';
	case DR = 'dr';
	case FL = 'fl';
	case FR = 'fr';
	case GE = 'ge';
	case GR = 'gr';
	case LI = 'li';
	case NH = 'nh';
	case OV = 'ov';
	case UT = 'ut';
	case ZE = 'ze';
	case ZH = 'zh';

	/** {@inheritDoc} */
	public function label(): string {
		return match ($this) {
			self::NB => __( 'Brabant', 'siw' ),
			self::DR => __( 'Drenthe', 'siw' ),
			self::FL => __( 'Flevoland', 'siw' ),
			self::FR => __( 'Friesland', 'siw' ),
			self::GE => __( 'Gelderland', 'siw' ),
			self::GR => __( 'Groningen', 'siw' ),
			self::LI => __( 'Limburg', 'siw' ),
			self::NH => __( 'Noord-Holland', 'siw' ),
			self::OV => __( 'Overijssel', 'siw' ),
			self::UT => __( 'Utrecht', 'siw' ),
			self::ZE => __( 'Zeeland', 'siw' ),
			self::ZH => __( 'Zuid-Holland', 'siw' ),
		};
	}
}



