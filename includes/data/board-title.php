<?php declare(strict_types=1);

namespace SIW\Data;

use \Spatie\Enum\Enum;

/**
 * Bestuursfuncties
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @method static self CHAIR() Voorzitter
 * @method static self SECRETARY() Secretaris
 * @method static self TREASURER() Penningmeester
 * @method static self BOARD_MEMBER() Bestuurslid
 */
class Board_Title extends Enum {

	/** {@inheritDoc} */
	protected static function values(): \Closure {
		return function( string $name ): string {
			return strtolower( $name );
		};
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'CHAIR'        => __( 'Voorzitter', 'siw' ),
			'SECRETARY'    => __( 'Secretaris' , 'siw' ),
			'TREASURER'    => __( 'Penningmeester' , 'siw' ),
			'BOARD_MEMBER' => __( 'Algemeen bestuurslid' , 'siw' ),
		];
	}
}