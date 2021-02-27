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
		'iso_code'    => 'BDI',
		'slug'        => 'burundi',
		'name'        => __( 'Burundi', 'siw' ),
		'allowed'     => false,
		'workcamps'   => false,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'bi', 'x' => 0.5794, 'y' => 0.7096 ],
	],
	[
		'iso_code'    => 'BWA',
		'slug'        => 'botswana',
		'name'        => __( 'Botswana', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'bw', 'x' => 0.5639, 'y' => 0.7901 ],
	],
	[
		'iso_code'    => 'GHA',
		'slug'        => 'ghana',
		'name'        => __( 'Ghana', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'gh', 'x' => 0.4984, 'y' => 0.6602 ],
	],
	[
		'iso_code'    => 'KEN',
		'slug'        => 'kenia',
		'name'        => __( 'Kenia', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'ke', 'x' => 0.6000, 'y' => 0.6931 ],
	],
	[
		'iso_code'    => 'MAR',
		'slug'        => 'marokko',
		'name'        => __( 'Marokko', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ma', 'x' => 0.4834, 'y' => 0.5547 ],
	],
	[
		'iso_code'    => 'MOZ',
		'slug'        => 'mozambique',
		'name'        => __( 'Mozambique', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'mz', 'x' => 0.5925, 'y' => 0.7671 ],
	],
	
	[
		'iso_code'    => 'SEN',
		'slug'        => 'senegal',
		'name'        => __( 'Senegal', 'siw' ),
		'allowed'     => false,
		'workcamps'   => false,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'sn', 'x' => 0.4623, 'y' => 0.6319 ],
	],
	[
		'iso_code'    => 'TGO',
		'slug'        => 'togo',
		'name'        => __( 'Togo', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'tg', 'x' => 0.5038, 'y' => 0.6559 ],
	],
	[
		'iso_code'    => 'TUN',
		'slug'        => 'tunesie',
		'name'        => __( 'Tunesië', 'siw' ),
		'allowed'     => false,
		'workcamps'   => false,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'tn', 'x' => 0.5264, 'y' => 0.5411 ],
	],
	[
		'iso_code'    => 'TZA',
		'slug'        => 'tanzania',
		'name'        => __( 'Tanzania', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'tz', 'x' => 0.5924, 'y' => 0.7217 ],
	],
	[
		'iso_code'    => 'UGA',
		'slug'        => 'uganda',
		'name'        => __( 'Uganda', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'ug', 'x' => 0.5863, 'y' => 0.6921 ],
	],
	[
		'iso_code'    => 'ZAF',
		'slug'        => 'zuid-afrika',
		'name'        => __( 'Zuid-Afrika', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'za', 'x' => 0.5654, 'y' => 0.8221 ],
	],
	[
		'iso_code'    => 'ZMB',
		'slug'        => 'zambia',
		'name'        => __( 'Zambia', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'zm', 'x' => 0.5733, 'y' => 0.7566 ],
	],
	[
		'iso_code'    => 'ZWE',
		'slug'        => 'zimbabwe',
		'name'        => __( 'Zimbabwe', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'zw', 'x' => 0.5791, 'y' => 0.7763 ],
	],
];

return $data;
