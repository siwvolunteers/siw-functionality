<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redux validatie: IP adres
 *
 * @param array $field
 * @param mixed $value
 * @param mixed $existing_value
 *
 * @return mixed
*/
function siw_settings_validate_ip( $field, $value, $existing_value ) {
	$filtered_value = filter_var( $value, FILTER_VALIDATE_IP );
	if ( $filtered_value == $value ) {
		$return['value'] = $value;
	}
	else {
		$field['msg'] = __( 'Dit is geen geldig IP-adres', 'siw' );
		$return['value'] = $existing_value;
		$return['error'] = $field;
	}
	return $return;
}
