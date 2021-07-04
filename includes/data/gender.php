<?php declare(strict_types=1);

namespace SIW\Data;

use \Spatie\Enum\Enum;

/**
 * Geslacht
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @method static self M() Man
 * @method static self F() Vrouw
 */
class Gender extends Enum {

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'M' => __( 'Man', 'siw' ),
			'F' => __( 'Vrouw', 'siw' ),
		];
	}
}
