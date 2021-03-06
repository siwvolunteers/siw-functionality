<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Latijns-Amerika
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'iso_code'    => 'ARG',
		'slug'        => 'argentinie',
		'name'        => __( 'Argentinië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'ar', 'x' => 0.3221, 'y' => 0.8591 ],
	],
	[
		'iso_code'    => 'BOL',
		'slug'        => 'bolivia',
		'name'        => __( 'Bolivia', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'bo', 'x' => 0.3260, 'y' => 0.7684 ],
	],
	[
		'iso_code'    => 'BRA',
		'slug'        => 'brazilie',
		'name'        => __( 'Brazilië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'br', 'x' => 0.3632, 'y' => 0.7413 ],
	],
	[
		'iso_code'    => 'COL',
		'slug'        => 'colombia',
		'name'        => __( 'Colombia', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'co', 'x' => 0.3050, 'y' => 0.6871 ],
	],
	[
		'iso_code'    => 'CRI',
		'slug'        => 'costa-rica',
		'name'        => __( 'Costa Rica', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'cr', 'x' => 0.2757, 'y' => 0.6574 ],
	],
	[
		'iso_code'    => 'ECU',
		'slug'        => 'ecuador',
		'name'        => __( 'Ecuador', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'ec', 'x' => 0.2903, 'y' => 0.7052 ],
	],
	[
		'iso_code'    => 'HTE',
		'slug'        => 'haiti',
		'name'        => __( 'Haïti', 'siw' ),
		'allowed'     => false,
		'workcamps'   => false,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ht', 'x' => 0.3060, 'y' => 0.6172 ],
	],
	[
		'iso_code'    => 'MEX',
		'slug'        => 'mexico',
		'name'        => __( 'Mexico', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'mx', 'x' => 0.2324, 'y' => 0.6099 ],
	],
	[
		'iso_code'    => 'PER',
		'slug'        => 'peru',
		'name'        => __( 'Peru', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'pe', 'x' => 0.2993, 'y' => 0.7419 ],
	],
];

return $data;