<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van valuta's
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$siw_data = [
	[
		'iso_code' => 'CAD',
		'symbol'   => 'C$',
		'name'     => __( 'Canadese dollar', 'siw' ),
	],
	[
		'iso_code' => 'CHF',
		'symbol'   => 'CHF',
		'name'     => __( 'Zwitserse frank', 'siw' ),
	],
	[
		'iso_code' => 'DKK',
		'symbol'   => 'kr.',
		'name'     => __( 'Deense kroon', 'siw' ),
	],
	[
		'iso_code' => 'EUR',
		'symbol'   => '&euro;',
		'name'     => __( 'Euro', 'siw' ),
	],
	[
		'iso_code' => 'GBP',
		'symbol'   => '&pound;',
		'name'     => __( 'Britse Pond', 'siw' ),
	],
	[
		'iso_code' => 'IDR',
		'symbol'   => 'Rp',
		'name'     => __( 'Indonesische roepia', 'siw' ),
	],
	[
		'iso_code' => 'INR',
		'symbol'   => '&#x20B9;',
		'name'     => __( 'Indiase roepie', 'siw' ),
	],
	[
		'iso_code' => 'JPY',
		'symbol'   => '&yen;',
		'name'     => __( 'Japanse yen', 'siw' ),
	],
	[
		'iso_code' => 'KES',
		'symbol'   => 'Ksh',
		'name'     => __( 'Keniaanse shilling', 'siw' ),
	],
	[
		'iso_code' => 'MXN',
		'symbol'   => '$',
		'name'     => __( 'Mexicaanse peso', 'siw' ),
	],
	[
		'iso_code' => 'MYR',
		'symbol'   => 'RM',
		'name'     => __( 'Maleisische ringgit', 'siw' ),
	],
	[
		'iso_code' => 'RUB',
		'symbol'   => '&#8381;',
		'name'     => __( 'Russische roebel', 'siw' ),
	],
	[
		'iso_code' => 'THB',
		'symbol'   => '&#x0E3F;',
		'name'     => __( 'Thaise baht', 'siw' ),
	],
	[
		'iso_code' => 'USD',
		'symbol'   => '$',
		'name'     => __( 'Amerikaanse dollar', 'siw' ),
	],
	[
		'iso_code' => 'VND',
		'symbol'   => '&#x20ab;',
		'name'     => __( 'Vietnamese dong', 'siw' ),
	],
];

return $siw_data;
