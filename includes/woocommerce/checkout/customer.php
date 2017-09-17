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

	$priorities = array(
		'first_name' => 10,
		'last_name' => 20,
		'dob' => 30,
		'gender' => 40,
		'postcode' => 65,
		'housenumber' => 70,
		'address_1' => 75,
		'city' => 80,
		'country' => 85,
		'nationality' => 90,
	);

	foreach ( $priorities as $field => $priority ) {
		$address_fields[ $field ]['priority'] = $priority;
	}

	return $address_fields;
}
