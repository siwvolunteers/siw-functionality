<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use \Spatie\Enum\Enum;

/**
 * Plato project types
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @method static self STV()
 * @method static self MTV()
 * @method static self LTV()
 * @method static self TEEN()
 * @method static self FAM()
 * @method static self VIRT()
 * @method static self EVS()
 * @method static self PER()
 * @method static self TRA()
 */
class Plato_Project_Type extends Enum {

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'STV'  => __( 'STV', 'siw' ),
			'MTV'  => __( 'MTV', 'siw' ),
			'LTV'  => __( 'LTV', 'siw' ),
			'TEEN' => __( 'Tienerproject', 'siw' ),
			'FAM'  => __( 'Familieproject', 'siw' ),
			'VIRT' => __( 'Virtueel project', 'siw' ),
			'EVS'  => __( 'EVS project', 'siw' ),
			'PER'  => __( 'Permanent project', 'siw' ),
			'TRA'  => __( 'Training', 'siw' ),
		];
	}
}
