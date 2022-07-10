<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use \Spatie\Enum\Enum;

/**
 * Doelgroepen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self FAMILIES()
 * @method static self TEENAGERS()
 */
class Target_Audience extends Enum {

	/** {@inheritDoc} */
	protected static function values(): array {
		return [
			'FAMILIES'  => 'families',
			'TEENAGERS' => 'tieners',
		];

	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'FAMILIES'  => __( 'Families', 'siw' ),
			'TEENAGERS' => __( 'Tieners', 'siw' ),
		];
	}
}
