<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Azië
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'iso_code'    => 'CHN',
		'slug'        => 'china',
		'name'        => __( 'China', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'cn', 'x' => 0.7782, 'y' => 0.5319 ],
	],
	[
		'iso_code'    => 'HKG',
		'slug'        => 'hong-kong',
		'name'        => __( 'Hong Kong', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'hk', 'x' => 0.7990, 'y' => 0.5998 ],
	],
	[
		'iso_code'    => 'IDN',
		'slug'        => 'indonesie',
		'name'        => __( 'Indonesië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'id', 'x' => 0.7697, 'y' => 0.7041 ],
	],
	[
		'iso_code'    => 'IND',
		'slug'        => 'india',
		'name'        => __( 'India', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'in', 'x' => 0.7066, 'y' => 0.6063 ],
	],
	[
		'iso_code'    => 'JPN',
		'slug'        => 'japan',
		'name'        => __( 'Japan', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'jp', 'x' => 0.8628, 'y' => 0.5337 ],
	],
	[
		'iso_code'    => 'KGZ',
		'slug'        => 'kirgizie',
		'name'        => __( 'Kirgizië', 'siw' ),
		'allowed'     => false,
		'workcamps'   => false,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'kg', 'x' => 0.6964, 'y' => 0.5047 ],
	],
	[
		'iso_code'    => 'KHM',
		'slug'        => 'cambodja',
		'name'        => __( 'Cambodja', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'kh', 'x' => 0.7765, 'y' => 0.6398 ],
	],
	[
		'iso_code'    => 'KOR',
		'slug'        => 'zuid-korea',
		'name'        => __( 'Zuid-Korea', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'kr', 'x' => 0.8348, 'y' => 0.5326 ],
	],
	[
		'iso_code'    => 'LKA',
		'slug'        => 'sri-lanka',
		'name'        => __( 'Sri Lanka', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'lk', 'x' => 0.7117, 'y' => 0.6624 ],
	],
	[
		'iso_code'    => 'LAO',
		'slug'        => 'laos',
		'name'        => __( 'Laos', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'la', 'x' => 0.7691, 'y' => 0.6171 ],
	],
	[
		'iso_code'    => 'MNG',
		'slug'        => 'mongolie',
		'name'        => __( 'Mongolië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'mn', 'x' => 0.7721, 'y' => 0.4785 ],
	],
	[
		'iso_code'    => 'MYS',
		'slug'        => 'maleisie',
		'name'        => __( 'Maleisië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'my', 'x' => 0.7675, 'y' => 0.6789 ],
	],
	
	[
		'iso_code'    => 'NPL',
		'slug'        => 'nepal',
		'name'        => __( 'Nepal', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'np', 'x' => 0.7200, 'y' => 0.5725 ]
	],
	[
		'iso_code'    => 'THA',
		'slug'        => 'thailand',
		'name'        => __( 'Thailand', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'th', 'x' => 0.7655, 'y' => 0.6310 ],
	],
	[
		'iso_code'    => 'TWN',
		'slug'        => 'taiwan',
		'name'        => __( 'Taiwan', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'tw', 'x' => 0.8170, 'y' => 0.5941 ],
	],
	[
		'iso_code'    => 'VNM',
		'slug'        => 'vietnam',
		'name'        => __( 'Vietnam', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => true,
		'world_map'   => [ 'code' => 'vn', 'x' => 0.7836, 'y' => 0.6352 ],
	],
	[
		'iso_code'    => 'PHL',
		'slug'        => 'filipijnen',
		'name'        => __( 'Filipijnen', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ph', 'x' => 0.8208, 'y' => 0.6447 ],
	],

	
];

return $data;