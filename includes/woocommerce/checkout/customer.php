<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function siw_sort_customer_address_fields( $address_fields ) {

	$gender = siw_get_volunteer_genders();
	$nationalities = siw_get_volunteer_nationalities();


	/* verwijderen velden */
	unset( $address_fields['address_2'] );
	unset( $address_fields['company'] );
	unset( $address_fields['state'] );


	$address_fields['gender'] = array(
		'label'			=> __( 'Geslacht', 'siw' ),
		'required'		=> true,
		'clear'			=> true,
		'type'			=> 'radio',
		'options'		=> $gender,
	);
	$address_fields['dob'] = array(
		'label'			=> __( 'Geboortedatum', 'siw' ),
		'required'		=> true,
		'type'			=> 'text',
    );
	$address_fields['nationality'] = array(
		'label'			=> __( 'Nationaliteit', 'siw' ),
		'required'		=> true,
		'type'  		=> 'select',
		'options'		=> $nationalities,
		'default'		=> 'HOL'
	);
	$address_fields['housenumber'] = array(
		'label'			=> __( 'Huisnummer', 'siw' ),
		'required'		=> true,
		'type'			=> 'text',
	);

	/*aanpassen eigenschappen standaard*/
	$address_fields['address_1']['label'] = __( 'Straat', 'siw' );

	$order = array(
		'first_name',
		'last_name',
		'dob',
		'gender',
		'postcode',
		'housenumber',
		'address_1',
		'city',
		'country',
		'nationality',
	);
	uksort( $address_fields, function( $key1, $key2 ) use ( $order ) {
		return (array_search( $key1, $order ) > array_search( $key2, $order ) );
	} );

	$priority = 10;
	foreach ( $address_fields as $key => $value ) {
		$priority += 10;
		$address_fields[$key]['priority'] = $priority;
	}
	return $address_fields;
}
