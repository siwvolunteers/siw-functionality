<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Geeft een array met valuta terug
 *
 * Bevat per valuta de volgende eigenschappen: symbol, name
 *
 * @return array
 */
function siw_get_currencies() {

	$project_currencies = array(
		'CHF' => array(
			'symbol'	=> 'CHF',
			'name'		=> __( 'Zwitserse frank', 'siw' ),
		),
		'DKK' => array(
			'symbol'	=> 'kr.',
			'name'		=> __( 'Deense kroon', 'siw' ),
		),
		'EUR' => array(
			'symbol'	=> '&euro;',
			'name'		=> __( 'Euro', 'siw' ),
		),
		'GBP' => array(
			'symbol'	=> '&pound;',
			'name'		=> __( 'Britse Pond', 'siw' ),
		),
		'IDR' => array(
			'symbol'	=> 'Rp',
			'name'		=> __( 'Indonesische roepia', 'siw' ),
		),
		'INR' => array(
			'symbol'	=> '&#x20B9;',
			'name'		=> __( 'Indiase roepie', 'siw' ),
		),
		'JPY' => array(
			'symbol'	=> '&yen;',
			'name'		=> __( 'Japanse yen', 'siw' ),
		),
		'KES' => array(
			'symbol'	=> 'Ksh',
			'name'		=> __( 'Keniaanse shilling', 'siw' ),
		),
		'MXN' => array(
			'symbol'	=> '$',
			'name'		=> __( 'Mexicaanse peso', 'siw' ),
		),
		'RUB' => array(
			'symbol'	=> '&#8381;',
			'name'		=> __( 'Russische roebel', 'siw' ),
		),
		'THB' => array(
			'symbol'	=> '&#x0E3F;',
			'name'		=> __( 'Thaise baht', 'siw' ),
		),
		'USD' => array(
			'symbol'	=> '$',
			'name'		=> __( 'Amerikaanse dollar', 'siw' ),
		),
		'VND' => array(
			'symbol'	=> '&#x20ab;',
			'name'		=> __( 'Vietnamese dong', 'siw' ),
		),
	);

	return $project_currencies;
}


/**
 * Haal eigenschappen van een valuta op
 * @param  string $currency_code
 * @return mixed
 */
function siw_get_currency( $currency_code ) {
	$currencies = siw_get_currencies();
	$currency = isset( $currencies[ $currency_code ] ) ? $currencies[ $currency_code ] : false;
	return $currency;
}
