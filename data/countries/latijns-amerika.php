<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Latijns-Amerika
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */

$siw_data = [
	[
		'plato_code'  => 'ARG',
		'iso_code'    => 'ar',
		'slug'        => 'argentinie',
		'name'        => __( 'Argentinië', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'BOL',
		'iso_code'    => 'bo',
		'slug'        => 'bolivia',
		'name'        => __( 'Bolivia', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'BRA',
		'iso_code'    => 'br',
		'slug'        => 'brazilie',
		'name'        => __( 'Brazilië', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'COL',
		'iso_code'    => 'co',
		'slug'        => 'colombia',
		'name'        => __( 'Colombia', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'CRI',
		'iso_code'    => 'cr',
		'slug'        => 'costa-rica',
		'name'        => __( 'Costa Rica', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'ECU',
		'iso_code'    => 'ec',
		'slug'        => 'ecuador',
		'name'        => __( 'Ecuador', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'HTE',
		'iso_code'    => 'ht',
		'slug'        => 'haiti',
		'name'        => __( 'Haïti', 'siw' ),
		'workcamps'   => false,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'MEX',
		'iso_code'    => 'mx',
		'slug'        => 'mexico',
		'name'        => __( 'Mexico', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'PER',
		'iso_code'    => 'pe',
		'slug'        => 'peru',
		'name'        => __( 'Peru', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
];

return $siw_data;
