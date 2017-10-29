<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* Opzoeken straat+plaats op basis van postcode+huisnummer via AJAX */
add_filter( 'siw_ajax_allowed_actions', function( $actions ) {
	$actions[] = 'postcode_lookup';
	return $actions;
});

add_action( 'siw_ajax_postcode_lookup', function() {

	check_ajax_referer( 'siw_ajax_nonce', 'security' );

	$api_key = siw_get_setting( 'postcode_api_key' );

	preg_match("/^[1-9][0-9]{3}[\s]?[A-Za-z]{2}$/i", $_GET['postcode'], $postcode );
	$postcode = strtoupper( str_replace(' ', '', $postcode[0] ) );
	$housenumber = preg_replace("/[^0-9]/", "", $_GET['housenumber'] );

	if ( ! $postcode || ! $housenumber ) {
		wp_send_json_error();
	}

	//TODO: transients ivm API limiet (en snelheid)


	$url = sprintf( 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=%s&number=%d', $postcode, $housenumber );

	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
		'headers'		=>array(
			'X-Api-Key'	=> $api_key,
		),
	);
	$response = wp_safe_remote_get( $url, $args );
	if ( is_wp_error( $response ) ) {
		wp_send_json_error();
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ) {
		wp_send_json_error();
	}

	$body = json_decode( wp_remote_retrieve_body( $response ) );

	if ( $body->_embedded->addresses ) {
		$street = $body->_embedded->addresses[0]->street;
		$city = $body->_embedded->addresses[0]->city->label;
		$data = array(
			'street'	=> $street,
			'city'		=> $city,
		);
		wp_send_json_success( $data );
	}
	else {
		wp_send_json_error();
	}

} );
