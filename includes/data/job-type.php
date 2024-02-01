<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Job_Type: string implements Labels {

	use Enum_List;

	case VOLUNTEER = 'volunteer';
	case PAID = 'paid';
	case INTERNSHIP = 'internship';

	#[\Override]
	public function label(): string {
		return match ( $this ) {
			self::VOLUNTEER  => __( 'Vrijwillige functie', 'siw' ),
			self::PAID       => __( 'Betaalde functie', 'siw' ),
			self::INTERNSHIP =>__( 'Stage', 'siw' ),
		};
	}
}
