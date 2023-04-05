<?php declare(strict_types=1);

use SIW\Util\CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van continenten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */

$siw_data = [
	[
		'slug'  => 'europa',
		'name'  => __( 'Europa', 'siw' ),
		'color' => CSS::BLUE_COLOR,
	],
	[
		'slug'  => 'azie',
		'name'  => __( 'Azië', 'siw' ),
		'color' => CSS::GREEN_COLOR,
	],
	[
		'slug'  => 'afrika',
		'name'  => __( 'Afrika', 'siw' ),
		'color' => CSS::RED_COLOR,
	],
	[
		'slug'  => 'latijns-amerika',
		'name'  => __( 'Latijns-Amerika', 'siw' ),
		'color' => CSS::PURPLE_COLOR,
	],
	[
		'slug'  => 'noord-amerika',
		'name'  => __( 'Noord-Amerika', 'siw' ),
		'color' => CSS::YELLOW_COLOR,
	],
];

return $siw_data;
