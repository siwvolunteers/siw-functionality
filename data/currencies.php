<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van valuta's
 * 
 * @package   SIW\Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

$data = [
	[
		'iso'    => 'CAD',
		'symbol' => 'C$',
		'name'   => __( 'Canadese dollar', 'siw' ),
	],
	[
		'iso'    => 'CHF',
		'symbol' => 'CHF',
		'name'   => __( 'Zwitserse frank', 'siw' ),
	],
	[
		'iso'    => 'DKK',
		'symbol' => 'kr.',
		'name'   => __( 'Deense kroon', 'siw' ),
	],
	[
		'iso'    => 'EUR',
		'symbol' => '&euro;',
		'name'   => __( 'Euro', 'siw' ),
	],
	[
		'iso'    => 'GBP',
		'symbol' => '&pound;',
		'name'   => __( 'Britse Pond', 'siw' ),
	],
	[
		'iso'    => 'IDR',
		'symbol' => 'Rp',
		'name'   => __( 'Indonesische roepia', 'siw' ),
	],
	[
		'iso'    => 'INR',
		'symbol' => '&#x20B9;',
		'name'   => __( 'Indiase roepie', 'siw' ),
	],
	[
		'iso'    => 'JPY',
		'symbol' => '&yen;',
		'name'   => __( 'Japanse yen', 'siw' ),
	],
	[
		'iso'    => 'KES',
		'symbol' => 'Ksh',
		'name'   => __( 'Keniaanse shilling', 'siw' ),
	],
	[
		'iso'    => 'MXN',
		'symbol' => '$',
		'name'   => __( 'Mexicaanse peso', 'siw' ),
	],
	[
		'iso'    => 'RUB',
		'symbol' => '&#8381;',
		'name'   => __( 'Russische roebel', 'siw' ),
	],
	[
		'iso'    => 'THB',
		'symbol' => '&#x0E3F;',
		'name'   => __( 'Thaise baht', 'siw' ),
	],
	[
		'iso'    => 'USD',
		'symbol' => '$',
		'name'   => __( 'Amerikaanse dollar', 'siw' ),
	],
	[
		'iso'    => 'VND',
		'symbol' => '&#x20ab;',
		'name'   => __( 'Vietnamese dong', 'siw' ),
	],
];

return $data;
