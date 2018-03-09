<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* Opzoeken straat+plaats op basis van postcode+huisnummer via AJAX */
siw_register_ajax_action( 'postcode_lookup' );

add_action( 'siw_ajax_postcode_lookup', function() {

	check_ajax_referer( 'siw_ajax_nonce', 'security' );

	preg_match("/^[1-9][0-9]{3}[\s]?[A-Za-z]{2}$/i", $_GET['postcode'], $postcode );
	$postcode = strtoupper( str_replace(' ', '', $postcode[0] ) );
	$housenumber = preg_replace("/[^0-9]/", "", $_GET['housenumber'] );

	if ( ! $postcode || ! $housenumber ) {
		wp_send_json_error();
	}

	$address = get_transient( "siw_address_{$postcode}_{$housenumber}" );
	if ( false === $address ) {
		$address = siw_get_address_from_postcode( $postcode, $housenumber );
		if ( false == $address ) {
			wp_send_json_error();
		}
		set_transient( "siw_address_{$postcode}_{$housenumber}", $address, MONTH_IN_SECONDS );
	}

	wp_send_json_success( $address );

} );


/**
 * Zoek adres op basis van postcode en huisnummer
 * @param  string $postcode
 * @param  string $housenumber
 * @return mixed $address
 */
function siw_get_address_from_postcode( $postcode, $housenumber ) {
	$api_key = siw_get_setting( 'postcode_api_key' );

	$url = add_query_arg( array(
		'postcode' => $postcode,
		'number' => $housenumber,
	), SIW_POSTCODE_API_URL );

	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
		'headers'		=>array(
			'X-Api-Key'	=> $api_key,
		),
	);
	$response = wp_safe_remote_get( $url, $args );
	if ( is_wp_error( $response ) ) {
		return false;
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ) {
		return false;
	}

	$body = json_decode( wp_remote_retrieve_body( $response ) );

	if ( $body->_embedded->addresses ) {
		$street = $body->_embedded->addresses[0]->street;
		$city = $body->_embedded->addresses[0]->city->label;
		$address = array(
			'street'	=> $street,
			'city'		=> $city,
		);
		return $address;
	}
	else {
		return false;
	}

	return false;
}
