<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Interfaces\Enums\Labels as I_Enum_Labels;

enum Target_Audience: string implements I_Enum_Labels {

	case FAMILIES = 'families';
	case TEENAGERS = 'tieners';

	/** {@inheritDoc} */
	public function label(): string {
		return match ( $this ) {
			self::FAMILIES => __( 'Families', 'siw' ),
			self::TEENAGERS => __( 'Tieners', 'siw' ),
		};
	}
}
