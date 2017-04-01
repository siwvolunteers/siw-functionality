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
function siw_get_project_currencies() {
	$project_currencies['CHF'] = array(
		'symbol'	=> 'CHF',
		'name'		=> __( 'Zwitserse frank', 'siw' ),
	);
	$project_currencies['DKK'] = array(
		'symbol'	=> 'kr.',
		'name'		=> __( 'Deense kroon', 'siw' ),
	);
	$project_currencies['EUR'] = array(
		'symbol'	=> '&euro;',
		'name'		=> __( 'Euro', 'siw' ),
	);
	$project_currencies['GBP'] = array(
		'symbol'	=> '&pound;',
		'name'		=> __( 'Britse Pond', 'siw' ),
	);
	$project_currencies['IDR'] = array(
		'symbol'	=> 'Rp',
		'name'		=> __( 'Indonesische roepia', 'siw' ),
	);
	$project_currencies['INR'] = array(
		'symbol'	=> '&#x20B9;',
		'name'		=> __( 'Indiase roepie', 'siw' ),
	);
	$project_currencies['JPY'] = array(
		'symbol'	=> '&yen;',
		'name'		=> __( 'Japanse yen', 'siw' ),
	);
	$project_currencies['KES'] = array(
		'symbol'	=> 'Ksh',
		'name'		=> __( 'Keniaanse shilling', 'siw' ),
	);
	$project_currencies['MXN'] = array(
		'symbol'	=> '$',
		'name'		=> __( 'Mexicaanse peso', 'siw' ),
	);
	$project_currencies['RUB'] = array(
		'symbol'	=> '&#8381;',
		'name'		=> __( 'Russische roebel', 'siw' ),
	);
	$project_currencies['THB'] = array(
		'symbol'	=> '&#x0E3F;',
		'name'		=> __( 'Thaise baht', 'siw' ),
	);
	$project_currencies['USD'] = array(
		'symbol'	=> '$',
		'name'		=> __( 'Amerikaanse dollar', 'siw' ),
	);
	$project_currencies['VND'] = array(
		'symbol'	=> '&#x20ab;',
		'name'		=> __( 'Vietnamese dong', 'siw' ),
	);

	return $project_currencies;
}
