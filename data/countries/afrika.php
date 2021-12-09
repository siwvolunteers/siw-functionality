<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Afrika
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'plato_code'  => 'BDI',
		'iso_code'    => 'bd',
		'slug'        => 'burundi',
		'name'        => __( 'Burundi', 'siw' ),
		'workcamps'   => false,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'BWA',
		'iso_code'    => 'bw',
		'slug'        => 'botswana',
		'name'        => __( 'Botswana', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'GHA',
		'iso_code'    => 'gh',
		'slug'        => 'ghana',
		'name'        => __( 'Ghana', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'KEN',
		'iso_code'    => 'ke',
		'slug'        => 'kenia',
		'name'        => __( 'Kenia', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'MAR',
		'iso_code'    => 'ma',
		'slug'        => 'marokko',
		'name'        => __( 'Marokko', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'MOZ',
		'iso_code'    => 'mz',
		'slug'        => 'mozambique',
		'name'        => __( 'Mozambique', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	
	[
		'plato_code'  => 'SEN',
		'iso_code'    => 'sn',
		'slug'        => 'senegal',
		'name'        => __( 'Senegal', 'siw' ),
		'workcamps'   => false,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'TGO',
		'iso_code'    => 'tg',
		'slug'        => 'togo',
		'name'        => __( 'Togo', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'TUN',
		'iso_code'    => 'tn',
		'slug'        => 'tunesie',
		'name'        => __( 'Tunesië', 'siw' ),
		'workcamps'   => false,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'TZA',
		'iso_code'    => 'tz',
		'slug'        => 'tanzania',
		'name'        => __( 'Tanzania', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'UGA',
		'iso_code'    => 'ug',
		'slug'        => 'uganda',
		'name'        => __( 'Uganda', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'ZAF',
		'iso_code'    => 'za',
		'slug'        => 'zuid-afrika',
		'name'        => __( 'Zuid-Afrika', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'ZMB',
		'iso_code'    => 'zm',
		'slug'        => 'zambia',
		'name'        => __( 'Zambia', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'ZWE',
		'iso_code'    => 'zw',
		'slug'        => 'zimbabwe',
		'name'        => __( 'Zimbabwe', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
];

return $data;
