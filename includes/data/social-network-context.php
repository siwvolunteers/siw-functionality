<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;
use SIW\Traits\Enum_List;

enum Social_Network_Context: string implements Labels {

	use Enum_List;

	case SHARE = 'share';
	case FOLLOW = 'follow';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::FOLLOW => __( 'Volgen', 'siw' ),
			self::SHARE  => __( 'Delen', 'siw' ),
		};
	}
}
