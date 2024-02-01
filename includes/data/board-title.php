<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Board_Title: string implements Labels {

	use Enum_List;

	case CHAIR = 'chair';
	case SECRETARY = 'secretary';
	case TREASURER = 'treasurer';
	case BOARD_MEMBER = 'board_member';

	#[\Override]
	public function label(): string {
		return match ( $this ) {
			self::CHAIR => __( 'Voorzitter', 'siw' ),
			self::SECRETARY => __( 'Secretaris', 'siw' ),
			self::TREASURER => __( 'Penningmeester', 'siw' ),
			self::BOARD_MEMBER => __( 'Algemeen bestuurslid', 'siw' ),
		};
	}
}
