<?php declare(strict_types=1);

namespace SIW\Data;

use \Spatie\Enum\Enum;

/**
 * Nederlandse provincies
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @method static self NB() Brabant
 * @method static self DR() Drenthe
 * @method static self FL() Flevoland
 * @method static self FR() Friesland
 * @method static self GE() Gelderland
 * @method static self GR() Groningen
 * @method static self LI() Limburg
 * @method static self NH() Noord-Holland
 * @method static self OV() Overijssel
 * @method static self UT() Utrecht
 * @method static self ZE() Zeeland
 * @method static self ZH() Zuid-Holland
 */
class Dutch_Province extends Enum {

	/** {@inheritDoc} */
	protected static function values(): \Closure {
		return function( string $name ): string {
			return strtolower( $name );
		};
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'NB' => __( 'Brabant', 'siw' ),
			'DR' => __( 'Drenthe', 'siw' ),
			'FL' => __( 'Flevoland', 'siw' ),
			'FR' => __( 'Friesland', 'siw' ),
			'GE' => __( 'Gelderland', 'siw' ),
			'GR' => __( 'Groningen', 'siw' ),
			'LI' => __( 'Limburg', 'siw' ),
			'NH' => __( 'Noord-Holland', 'siw' ),
			'OV' => __( 'Overijssel', 'siw' ),
			'UT' => __( 'Utrecht', 'siw' ),
			'ZE' => __( 'Zeeland', 'siw' ),
			'ZH' => __( 'Zuid-Holland', 'siw' ),
		];
	}
}