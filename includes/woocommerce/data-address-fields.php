<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen adres-velden tijdens checkout
 * 
 * @author      Maarten Bruna
 * @package     SIW\Checkout
 * @copyright   2018, SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_address_fields', function( $standard_address_fields ) {

	/* Verwijderen standaardvelden */
	unset( $standard_address_fields['address_2'] );
	unset( $standard_address_fields['company'] );
	unset( $standard_address_fields['state'] );

	/* Reset alle classes */
	$standard_address_fields = array_map( function( $field ) {
		unset( $field['class']);
		return $field;
	}, $standard_address_fields);


	$address_fields = [
		'first_name' => [
			'class'       => ['form-row-first'],
			'priority'    => 10,
		],
		'last_name' => [
			'class'       => ['form-row-last'],
			'priority'    => 20,
		],
		'dob' => [
			'label'       => __( 'Geboortedatum', 'siw' ),
			'required'    => true,
			'type'        => 'text',
			'class'       => ['form-row-first'],
			'input_class' => ['dateNL'],
			'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
			'priority'    => 30,
		],
		'gender' => [
			'label'       => __( 'Geslacht', 'siw' ),
			'required'    => true,
			'type'        => 'radio',
			'options'     => siw_get_genders(),
			'class'       => ['form-row-last'],
			'label_class' => ['radio-label'],
			'priority'    => 40,
		],
		'postcode' => [
			'class'       => ['form-row-first'],
			'input_class' => ['postalcodeNL'],
			'placeholder' => '1234 AB',
			'priority'    => 65,
		],
		'housenumber' => [
			'label'       => __( 'Huisnummer', 'siw' ),
			'required'    => true,
			'type'        => 'text',
			'class'       => ['form-row-last'],
			'priority'    => 70,
		],
		'address_1' => [
			'label'       => __( 'Straat', 'siw' ),
			'class'       => ['form-row-first'],
			'placeholder' => '',
			'priority'    => 75,
		],
		'city' => [
			'class'       => ['form-row-last'],
			'priority'    => 80,
		],
		'country' => [
			'class'       => ['form-row-first', 'country', 'select'],
			'label_class' => ['select-label'],
			//'description' => __( 'Het is alleen mogelijk om je aan te melden als je in Nederland woont.', 'siw' ),
			'priority'    => 85,
		],
		'nationality' => [
			'label'       => __( 'Nationaliteit', 'siw' ),
			'required'    => true,
			'type'        => 'select',
			'options'     => siw_get_nationalities(),
			'default'     => 'HOL',
			'class'       => ['form-row-last', 'select'],
			'label_class' => ['select-label'],
			'priority'    => 90,
		],
	];
	
	$address_fields = wp_parse_args_recursive( $address_fields, $standard_address_fields );

	return $address_fields;
});