<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Europa
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'iso'         => 'ALB',
		'slug'        => 'albanie',
		'name'        => __( 'Albanië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'al', 'x' => 0.5536, 'y' => 0.5095 ],
	],
	[
		'iso'         => 'ARM',
		'slug'        => 'armenie',
		'name'        => __( 'Armenië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'am', 'x' => 0.6181, 'y' => 0.5107 ],
	],
	[
		'iso'         => 'AUT',
		'slug'        => 'oostenrijk',
		'name'        => __( 'Oostenrijk', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'at', 'x' => 0.5101, 'y' => 0.6117 ],
		'world_map'   => [ 'code' => 'at', 'x' => 0.5398, 'y' => 0.4681 ],
	],
	[
		'iso'         => 'BEL',
		'slug'        => 'belgie',
		'name'        => __( 'België', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'be', 'x' => 0.3565, 'y' => 0.5327 ],
		'world_map'   => [ 'code' => 'be', 'x' => 0.5148, 'y' => 0.4505 ],
	],
	[
		'iso'         => 'BGR',
		'slug'        => 'bulgarije',
		'name'        => __( 'Bulgarije', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'bg', 'x' => 0.7005, 'y' => 0.7120 ],
		'world_map'   => [ 'code' => 'bg', 'x' => 0.5672, 'y' => 0.4986 ],
	],
	[
		'iso'         => 'BLR',
		'slug'        => 'wit-rusland',
		'name'        => __( 'Wit-Rusland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'by', 'x' => 0.5737, 'y' => 0.4297 ],
	],
	[
		'iso'         => 'CHE',
		'slug'        => 'zwitserland',
		'name'        => __( 'Zwitserland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ch', 'x' => 0.5236, 'y' => 0.4773 ],
	],
	[
		'iso'         => 'CYP',
		'slug'        => 'cyprus',
		'name'        => __( 'Cyprus', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'cy', 'x' => 0.9060, 'y' => 0.8622 ],
		'world_map'   => [ 'code'	=> 'cy', 'x' => 0.5880, 'y' => 0.5406 ],
	],
	[
		'iso'         => 'CZE',
		'slug'        => 'tsjechie',
		'name'        => __( 'Tsjechië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'europe_map'  => [ 'code' => 'cz', 'x' => 0.5209, 'y' => 0.5620 ],
		'world_map'   => [ 'code'	=> 'cz', 'x' => 0.5421, 'y' => 0.4588 ],
	],
	[
		'iso'         => 'DEU',
		'slug'        => 'duitsland',
		'name'        => __( 'Duitsland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'de', 'x' => 0.4383, 'y' => 0.4949 ],
		'world_map'   => [ 'code' => 'de', 'x' => 0.5285, 'y' => 0.4509 ],
	],
	[
		'iso'         => 'DNK',
		'slug'        => 'denemarken',
		'name'        => __( 'Denemarken', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'dk', 'x' => 0.4353, 'y' => 0.3948 ],
		'world_map'   => [ 'code' => 'dk', 'x' => 0.5252, 'y' => 0.4146 ],
	],
	[
		'iso'         => 'ESP',
		'slug'        => 'spanje',
		'name'        => __( 'Spanje', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'es', 'x' => 0.1731, 'y' => 0.7856 ],
		'world_map'   => [ 'code' => 'es', 'x' => 0.4931, 'y' => 0.5175 ],
	],
	[
		'iso'         => 'EST',
		'slug'        => 'estland',
		'name'        => __( 'Estland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'ew', 'x' => 0.6372, 'y' => 0.3070 ],
		'world_map'   => [ 'code'	=> 'ee', 'x' => 0.5687, 'y' => 0.3964 ],
	],
	[
		'iso'         => 'FIN',
		'slug'        => 'finland',
		'name'        => __( 'Finland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'no', 'x' => 0.6052, 'y' => 0.2487 ],
		'world_map'   => [ 'code' => 'fi', 'x' => 0.5723, 'y' => 0.3538 ],
	],
	[
		'iso'         => 'FRA',
		'slug'        => 'frankrijk',
		'name'        => __( 'Frankrijk', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'fr', 'x' => 0.3084, 'y' => 0.6348 ],
		'world_map'   => [ 'code' => 'fr', 'x' => 0.5087, 'y' => 0.4806 ],
	],
	[
		'iso'         => 'GBR',
		'slug'        => 'verenigd-koninkrijk',
		'name'        => __( 'Verenigd Koninkrijk', 'siw' ),
		'allowed'     => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'gb', 'x' => 0.2680, 'y' => 0.4444 ],
		'world_map'   => [ 'code' => 'gb', 'x' => 0.4974, 'y' => 0.4338 ],
	],
	[
		'iso'         => 'GEO',
		'slug'        => 'georgie',
		'name'        => __( 'Georgië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ge', 'x' => 0.6127, 'y' => 0.5001 ],
	],
	[
		'iso'         => 'GRC',
		'slug'        => 'griekenland',
		'name'        => __( 'Griekenland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'gr', 'x' => 0.6743, 'y' => 0.8102 ],
		'world_map'   => [ 'code' => 'gr', 'x' => 0.5589, 'y' => 0.5204 ],
	],
	[
		'iso'         => 'HUN',
		'slug'        => 'hongarije',
		'name'        => __( 'Hongarije', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'hu', 'x' => 0.5892, 'y' => 0.6179 ],
		'world_map'   => [ 'code' => 'hu', 'x' => 0.5524, 'y' => 0.4751 ],
	],
	[
		'iso'         => 'HRV',
		'slug'        => 'kroatie',
		'name'        => __( 'Kroatië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'hr', 'x' => 0.5467, 'y' => 0.6570 ],
		'world_map'   => [ 'code' => 'hr', 'x' => 0.5435, 'y' => 0.4848 ],
	],
	[
		'iso'         => 'IRL',
		'slug'        => 'ierland',
		'name'        => __( 'Ierland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'ie', 'x' => 0.1829, 'y' => 0.4456 ],
		'world_map'   => [ 'code' => 'ie', 'x' => 0.4812, 'y' => 0.4345 ],
	],
	[
		'iso'         => 'ISL',
		'slug'        => 'ijsland',
		'name'        => __( 'IJsland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'is', 'x' => 0.1391, 'y' => 0.1429 ],
		'world_map'   => [ 'code' => 'is', 'x' => 0.4504, 'y' => 0.3412 ],
	],
	[
		'iso'         => 'ITA',
		'slug'        => 'italie',
		'name'        => __( 'Italië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'it', 'x' => 0.4686, 'y' => 0.7159 ],
		'world_map'   => [ 'code' => 'it', 'x' => 0.5341, 'y' => 0.5014 ],
	],
	[
		'iso'         => 'LIE',
		'slug'        => 'liechtenstein',
		'name'        => __( 'Liechtenstein', 'siw' ),
		'allowed'     => true,      
		'workcamps'   => false,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'li', 'x' => 0.4311, 'y' => 0.6250 ],
		'world_map'   => [ 'code' => 'li', 'x' => 0.5273, 'y' => 0.4742 ],
	],
	[
		'iso'         => 'LTU',
		'slug'        => 'litouwen',
		'name'        => __( 'Litouwen', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'lt', 'x' => 0.6288, 'y' => 0.4005 ],
		'world_map'   => [ 'code' => 'lt', 'x' => 0.5643, 'y' => 0.4206 ],
	],
	[
		'iso'         => 'LUX',
		'slug'        => 'luxemburg',
		'name'        => __( 'Luxemburg', 'siw' ),
		'allowed'     => true,
		'workcamps'   => false,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'lu', 'x' => 0.3773, 'y' => 0.5592 ],
		'world_map'   => [ 'code' => 'lu', 'x' => 0.5177, 'y' => 0.4575 ],
	],
	[
		'iso'         => 'LVA',
		'slug'        => 'letland',
		'name'        => __( 'Letland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'lv', 'x' => 0.6457, 'y' => 0.3567 ],
		'world_map'   => [ 'code' => 'lv', 'x' => 0.5712, 'y' => 0.4061 ],
	],
	[
		'iso'         => 'MKD',
		'slug'        => 'macedonie',
		'name'        => __( 'Macedonië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => false,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'mk', 'x' => 0.6491, 'y' => 0.7496 ],
		'world_map'   => [ 'code' => 'mk', 'x' => 0.5592, 'y' => 0.5048 ],
	],
	[
		'iso'         => 'MLT',
		'slug'        => 'malta',
		'name'        => __( 'Malta', 'siw' ),
		'allowed'     => true,
		'workcamps'   => false,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'mt', 'x' => 0.5277, 'y' => 0.9087 ],
		'world_map'   => [ 'code' => 'mt', 'x' => 0.5390, 'y' => 0.5358 ],
	],
	[
		'iso'         => 'MNE',
		'slug'        => 'montenegro',
		'name'        => __( 'Montenegro', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'me', 'x' => 0.5516, 'y' => 0.4981 ],
	],
	[
		'iso'         => 'NLD',
		'slug'        => 'nederland',
		'name'        => __( 'Nederland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'nl', 'x' => 0.5173, 'y' => 0.4410 ],
	],
	[
		'iso'         => 'NOR',
		'slug'        => 'noorwegen',
		'name'        => __( 'Noorwegen', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'fi', 'x' => 0.4299, 'y' => 0.2622 ],
		'world_map'   => [ 'code' => 'no', 'x' => 0.5251, 'y' => 0.3675 ],
	],
	[
		'iso'         => 'POL',
		'slug'        => 'polen',
		'name'        => __( 'Polen', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'pl', 'x' => 0.5656, 'y' => 0.4748 ],
		'world_map' 	=> [ 'code' => 'pl', 'x' => 0.5517, 'y' => 0.4426 ],
	],
	[
		'iso'         => 'PRT',
		'slug'        => 'portugal',
		'name'        => __( 'Portugal', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'pt', 'x' => 0.0999, 'y' => 0.7642 ],
		'world_map'   => [ 'code' => 'pt', 'x' => 0.4810, 'y' => 0.5162 ],
	],
	[
		'iso'         => 'ROU',
		'slug'        => 'roemenie',
		'name'        => __( 'Roemenië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map' 	=> [ 'code' => 'ro', 'x' => 0.6946, 'y' => 0.6155 ],
		'world_map'   => [ 'code'	=> 'ro', 'x' => 0.5668, 'y' => 0.4790 ],
	],
	[
		'iso'         => 'RUS',
		'slug'        => 'rusland',
		'name'        => __( 'Rusland', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ru', 'x' => 0.6445, 'y' => 0.3841 ],
	],
	[
		'iso'         => 'SRB',
		'slug'        => 'servie',
		'name'        => __( 'Servië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'rs', 'x' => 0.5555, 'y' => 0.4914 ],
	],
	[
		'iso'         => 'SVK',
		'slug'        => 'slowakije',
		'name'        => __( 'Slowakije', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'sk', 'x' => 0.5815, 'y' => 0.5764 ],
		'world_map'   => [ 'code' => 'sk', 'x' => 0.5526, 'y' => 0.4643 ],
	],
	[
		'iso'         => 'SVN',
		'slug'        => 'slovenie',
		'name'        => __( 'Slovenië', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'si', 'x' => 0.5192, 'y' => 0.6513 ],
		'world_map'   => [ 'code' => 'si', 'x' => 0.5399, 'y' => 0.4796 ],
	],
	[
		'iso'         => 'SWE',
		'slug'        => 'zweden',
		'name'        => __( 'Zweden', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'se', 'x' => 0.4923, 'y' => 0.2976 ],
		'world_map'   => [ 'code' => 'se', 'x' => 0.5410, 'y' => 0.3618 ],
	],
	[
		'iso'         => 'TUR',
		'slug'        => 'turkije',
		'name'        => __( 'Turkije', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'esc'         => true,
		'europe_map'  => [ 'code' => 'tr', 'x' => 0.8529, 'y' => 0.7596 ],
		'world_map'   => [ 'code' => 'tr', 'x' => 0.5926, 'y' => 0.5203 ],
	],
	[
		'iso'         => 'UKR',
		'slug'        => 'oekraine',
		'name'        => __( 'Oekraïne', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ua', 'x' => 0.5856, 'y' => 0.4593 ],
	],
	[
		'iso'         => 'XKS',
		'slug'        => 'kosovo',
		'name'        => __( 'Kosovo', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'xk', 'x' => 0.5555, 'y' => 0.5006 ],
	],
];
return $data;