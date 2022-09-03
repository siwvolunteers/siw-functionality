<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van valuta's
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
// phpcs:disable WordPress.WP.I18n.TextDomainMismatch
$siw_data = [
	[
		'iso_code' => 'CAD',
		'symbol'   => 'C$',
		'name'     => __( 'Canadian dollar', 'woocommerce' ),
	],
	[
		'iso_code' => 'CHF',
		'symbol'   => 'CHF',
		'name'     => __( 'Swiss franc', 'woocommerce' ),
	],
	[
		'iso_code' => 'DKK',
		'symbol'   => 'kr.',
		'name'     => __( 'Danish krone', 'woocommerce' ),
	],
	[
		'iso_code' => 'EUR',
		'symbol'   => '&euro;',
		'name'     => __( 'Euro', 'woocommerce' ),
	],
	[
		'iso_code' => 'GBP',
		'symbol'   => '&pound;',
		'name'     => __( 'Pound sterling', 'woocommerce' ),
	],
	[
		'iso_code' => 'IDR',
		'symbol'   => 'Rp',
		'name'     => __( 'Indonesian rupiah', 'woocommerce' ),
	],
	[
		'iso_code' => 'INR',
		'symbol'   => '&#x20B9;',
		'name'     => __( 'Indian rupee', 'woocommerce' ),
	],
	[
		'iso_code' => 'JPY',
		'symbol'   => '&yen;',
		'name'     => __( 'Japanese yen', 'woocommerce' ),
	],
	[
		'iso_code' => 'KES',
		'symbol'   => 'Ksh',
		'name'     => __( 'Kenyan shilling', 'woocommerce' ),
	],
	[
		'iso_code' => 'MXN',
		'symbol'   => '$',
		'name'     => __( 'Mexican peso', 'woocommerce' ),
	],
	[
		'iso_code' => 'MYR',
		'symbol'   => 'RM',
		'name'     => __( 'Malaysian ringgit', 'woocommerce' ),
	],
	[
		'iso_code' => 'RUB',
		'symbol'   => '&#8381;',
		'name'     => __( 'Russian ruble', 'woocommerce' ),
	],
	[
		'iso_code' => 'THB',
		'symbol'   => '&#x0E3F;',
		'name'     => __( 'Thai baht', 'woocommerce' ),
	],
	[
		'iso_code' => 'USD',
		'symbol'   => '$',
		'name'     => __( 'United States (US) dollar', 'woocommerce' ),
	],
	[
		'iso_code' => 'VND',
		'symbol'   => '&#x20ab;',
		'name'     => __( 'Vietnamese &#x111;&#x1ed3;ng', 'woocommerce' ),
	],
];
// phpcs:enable WordPress.WP.I18n.TextDomainMismatch

return $siw_data;
