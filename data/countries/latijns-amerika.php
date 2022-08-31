<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Latijns-Amerika
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
$siw_data = [
	[
		'plato_code'  => 'ARG',
		'iso_code'    => 'ar',
		'slug'        => 'argentinie',
		'name'        => __( 'Argentina', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'BOL',
		'iso_code'    => 'bo',
		'slug'        => 'bolivia',
		'name'        => __( 'Bolivia', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'BRA',
		'iso_code'    => 'br',
		'slug'        => 'brazilie',
		'name'        => __( 'Brazil', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'COL',
		'iso_code'    => 'co',
		'slug'        => 'colombia',
		'name'        => __( 'Colombia', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'CRI',
		'iso_code'    => 'cr',
		'slug'        => 'costa-rica',
		'name'        => __( 'Costa Rica', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'ECU',
		'iso_code'    => 'ec',
		'slug'        => 'ecuador',
		'name'        => __( 'Ecuador', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'HTE',
		'iso_code'    => 'ht',
		'slug'        => 'haiti',
		'name'        => __( 'Haiti', 'woocommerce' ),
		'workcamps'   => false,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'MEX',
		'iso_code'    => 'mx',
		'slug'        => 'mexico',
		'name'        => __( 'Mexico', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
	[
		'plato_code'  => 'PER',
		'iso_code'    => 'pe',
		'slug'        => 'peru',
		'name'        => __( 'Peru', 'woocommerce' ),
		'workcamps'   => true,
		'tailor_made' => true,
	],
];
// phpcs:enable WordPress.WP.I18n.TextDomainMismatch
return $siw_data;
