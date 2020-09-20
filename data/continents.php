<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van continenten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'slug'  => 'europa',
		'name'  => __( 'Europa', 'siw' ),
		'color' => '#007499',
	],
	[
		'slug'  => 'azie',
		'name'  => __( 'Azië', 'siw' ),
		'color' => '#008e3f',
	],
	[
		'slug'  => 'afrika',
		'name'  => __( 'Afrika', 'siw' ),
		'color' => '#e30613',
	],
	[
		'slug'  => 'latijns-amerika',
		'name'  => __( 'Latijns-Amerika', 'siw' ),
		'color' => '#c7017f',
	],
	[
		'slug'  => 'noord-amerika',
		'name'  => __( 'Noord-Amerika', 'siw' ),
		'color' => '#fbba00',
	],
];

return $data;
