<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use \Spatie\Enum\Enum;

/**
 * WooCommerce product attributes
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @method static self PROJECT_NAME()
 * @method static self PROJECT_CODE()
 * @method static self START_DATE()
 * @method static self END_DATE()
 * @method static self NUMBER_OF_VOLUNTEERS()
 * @method static self AGE_RANGE()
 * @method static self PARTICIPATION_FEE()
 */
class Product_Attribute extends Enum {

	/** {@inheritDoc} */
	protected static function values(): array {
		return [
			'PROJECT_NAME'         => 'projectnaam',
			'PROJECT_CODE'         => 'projectcode',
			'START_DATE'           => 'startdatum',
			'END_DATE'             => 'einddatum',
			'NUMBER_OF_VOLUNTEERS' => 'aantal-vrijwilligers',
			'AGE_RANGE'            => 'leeftijd',
			'PARTICIPATION_FEE'    => 'lokale-bijdrage',
		];
	}

	/** {@inheritDoc} */
	protected static function labels(): array {
		return [
			'PROJECT_NAME'         => __( 'Projectnaam', 'siw' ),
			'PROJECT_CODE'         => __( 'Projectcode', 'siw' ),
			'START_DATE'           => __( 'Startdatum', 'siw' ),
			'END_DATE'             => __( 'Einddatum', 'siw' ),
			'NUMBER_OF_VOLUNTEERS' => __( 'Aantal vrijwilligers', 'siw' ),
			'AGE_RANGE'            => __( 'Leeftijd', 'siw' ),
			'PARTICIPATION_FEE'    => __( 'Lokale bijdrage', 'siw' ),
		];
	}
}
