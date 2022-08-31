<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Azië
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
$siw_data = [
	[
		'plato_code'  => 'CHN',
		'iso_code'    => 'cn',
		'slug'        => 'china',
		'name'        => __( 'China', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [
			'x' => 0.7782,
			'y' => 0.5319,
		], // TODO: kan weg na mapplic fix
	],
	[
		'plato_code'  => 'HKG',
		'iso_code'    => 'hk',
		'slug'        => 'hong-kong',
		'name'        => __( 'Hong Kong', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [
			'x' => 0.7990,
			'y' => 0.5998,
		], // FIXME: staat eigenlijk niet op de kaart
	],
	[
		'plato_code'  => 'IDN',
		'iso_code'    => 'id',
		'slug'        => 'indonesie',
		'name'        => __( 'Indonesia', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'IND',
		'iso_code'    => 'in',
		'slug'        => 'india',
		'name'        => __( 'India', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'JPN',
		'iso_code'    => 'jp',
		'slug'        => 'japan',
		'name'        => __( 'Japan', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'KGZ',
		'iso_code'    => 'kg',
		'slug'        => 'kirgizie',
		'name'        => __( 'Kyrgyzstan', 'woocommerce' ),
		'workcamps'   => false,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'KHM',
		'iso_code'    => 'kh',
		'slug'        => 'cambodja',
		'name'        => __( 'Cambodia', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'KOR',
		'iso_code'    => 'kr',
		'slug'        => 'zuid-korea',
		'name'        => __( 'South Korea', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'LKA',
		'iso_code'    => 'lk',
		'slug'        => 'sri-lanka',
		'name'        => __( 'Sri Lanka', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'LAO',
		'iso_code'    => 'la',
		'slug'        => 'laos',
		'name'        => __( 'Laos', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'MNG',
		'iso_code'    => 'mn',
		'slug'        => 'mongolie',
		'name'        => __( 'Mongolia', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'MYS',
		'iso_code'    => 'my',
		'slug'        => 'maleisie',
		'name'        => __( 'Malaysia', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],

	[
		'plato_code'  => 'NPL',
		'iso_code'    => 'np',
		'slug'        => 'nepal',
		'name'        => __( 'Nepal', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'THA',
		'iso_code'    => 'th',
		'slug'        => 'thailand',
		'name'        => __( 'Thailand', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'TWN',
		'iso_code'    => 'tw',
		'slug'        => 'taiwan',
		'name'        => __( 'Taiwan', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'VNM',
		'iso_code'    => 'vn',
		'slug'        => 'vietnam',
		'name'        => __( 'Vietnam', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'PHL',
		'iso_code'    => 'ph',
		'slug'        => 'filipijnen',
		'name'        => __( 'Philippines', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
];
// phpcs:enable WordPress.WP.I18n.TextDomainMismatch
return $siw_data;
