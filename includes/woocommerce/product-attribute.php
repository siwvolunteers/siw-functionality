<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Interfaces\Enums\Labels as I_Enum_Labels;

enum Product_Attribute: string implements I_Enum_Labels {

	case PROJECT_NAME         = 'projectnaam';
	case PROJECT_CODE         = 'projectcode';
	case START_DATE           = 'startdatum';
	case END_DATE             = 'einddatum';
	case NUMBER_OF_VOLUNTEERS = 'aantal-vrijwilligers';
	case AGE_RANGE            = 'leeftijd';
	case PARTICIPATION_FEE    = 'lokale-bijdrage';


	#[\Override]
	public function label(): string {
		return match ( $this ) {
			self::PROJECT_NAME         => __( 'Projectnaam', 'siw' ),
			self::PROJECT_CODE         => __( 'Projectcode', 'siw' ),
			self::START_DATE           => __( 'Startdatum', 'siw' ),
			self::END_DATE             => __( 'Einddatum', 'siw' ),
			self::NUMBER_OF_VOLUNTEERS => __( 'Aantal vrijwilligers', 'siw' ),
			self::AGE_RANGE            => __( 'Leeftijd', 'siw' ),
			self::PARTICIPATION_FEE    => __( 'Lokale bijdrage', 'siw' ),
		};
	}
}
