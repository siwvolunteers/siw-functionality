<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van continenten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'slug'  => 'europa',
		'name'  => __( 'Europa', 'siw' ),
		'color' => '#67bdd3',
	],
	[
		'slug'  => 'azie',
		'name'  => __( 'Azië', 'siw' ),
		'color' => '#7fc31b',
	],
	[
		'slug'  => 'afrika',
		'name'  => __( 'Afrika', 'siw' ),
		'color' => '#e74052',
	],
	[
		'slug'  => 'latijns-amerika',
		'name'  => __( 'Latijns-Amerika', 'siw' ),
		'color' => '#623981',
	],
	[
		'slug'  => 'noord-amerika',
		'name'  => __( 'Noord-Amerika', 'siw' ),
		'color' => '#f4d416',
	],
];

return $data;
