<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use \Spatie\Enum\Enum;

/**
 * Projectduur
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @method static self STV()
 * @method static self MTV()
 * @method static self LTV()
 */
class Project_Duration extends Enum {

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'STV'  => __( '7-29 dagen', 'siw' ),
			'MTV'  => __( '30-89 dagen', 'siw' ),
			'LTV'  => __( '90-365 dagen', 'siw' ),
		];
	}
}
