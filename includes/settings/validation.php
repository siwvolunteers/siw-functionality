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


/**
 * Redux validatie: Breedtegraad
 *
 * @param array $field
 * @param mixed $value
 * @param mixed $existing_value
 *
 * @return mixed
*/
function siw_settings_validate_latitude( $field, $value, $existing_value ) {
	$filtered_value = preg_grep("/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/", array( $value ) );
	$filtered_value = isset( $filtered_value[0] ) ? $filtered_value[0] : '' ;

	if ( empty( $value ) || $filtered_value == $value ) {
		$return['value'] = $value;
	}
	else {
		$field['msg'] = __( 'Dit is geen geldige breedtegraad', 'siw' );
		$return['value'] = $existing_value;
		$return['error'] = $field;
	}
	return $return;
}


/**
 * Redux validatie: Lengtegraad
 *
 * @param array $field
 * @param mixed $value
 * @param mixed $existing_value
 *
 * @return mixed
*/
function siw_settings_validate_longitude( $field, $value, $existing_value ) {
	$filtered_value = preg_grep("/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/", array( $value ) );
	$filtered_value = isset( $filtered_value[0] ) ? $filtered_value[0] : '' ;

	if ( empty( $value ) || $filtered_value == $value ) {
		$return['value'] = $value;
	}
	else {
		$field['msg'] = __( 'Dit is geen geldige lengtegraad', 'siw' );
		$return['value'] = $existing_value;
		$return['error'] = $field;
	}
	return $return;
}
