<?php declare(strict_types=1);

namespace SIW\Data;

use SIW\Interfaces\Enums\Labels;

enum Gender: string implements Labels {
	case MALE = 'M';
	case FEMALE = 'F';


	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::MALE => __( 'Man', 'siw' ),
			self::FEMALE => __( 'Vrouw', 'siw' ),
		};
	}
}
