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

	$api_key = siw_get_setting( 'postcode_api_key' );
	$postcode = strtoupper( siw_strip_url( $_GET['postcode'] ) );
	$housenumber = siw_strip_url( $_GET['housenumber'] );

	$url = 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=' . str_replace(' ', '', $postcode ) . '&number=' . $housenumber;
	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
		'headers'		=> array(
			'X-Api-Key'	=> $api_key,
			),
	);
	$response = wp_safe_remote_get( $url, $args );
	if ( is_wp_error( $response ) ) {
		wp_send_json_error();
	}

	$statuscode = wp_remote_retrieve_response_code( $response );
	if ( 200 != $statuscode ){
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

//TODO betere functie voor schrijven, bijv splitsen
function siw_strip_url( $title , $seperator = '-' ) {
	$title = preg_replace( '/[^a-z0-9\s]/i', '' , $title );

	if ( ! empty( $title ) && strlen( $title ) <= 6 ) {
		return $title;
	}
	else {
 		return false;
	}
}
