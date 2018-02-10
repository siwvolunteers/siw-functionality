<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Haal gegevens van aanmelding op
 * @param  object $order
 * @return array
 */
function siw_get_order_data( $order ) {

	/* Hulplijstjes */
	$genders = siw_get_volunteer_genders();
	$nationalities = siw_get_volunteer_nationalities();
	$languages = siw_get_volunteer_languages();
	$language_skill = siw_get_volunteer_language_skill_levels();

	$order_data = array(
		'first_name'				=> $order->get_billing_first_name(),
		'last_name'					=> $order->get_billing_last_name(),
		'date_of_birth'				=> $order->get_meta( '_billing_dob' ),
		'gender_code'				=> $order->get_meta( '_billing_gender' ),
		'gender'					=> $genders[ $order_data['gender_code'] ],
		'nationality_code'			=> $order->get_meta( '_billing_nationality' ),
		'street' 					=> $order->get_billing_address_1(),
		'housenumber' 				=> $order->get_meta( '_billing_housenumber' ),
		'postcode'	 				=> $order->get_billing_postcode(),
		'city'	 					=> $order->get_billing_city(),
		'country'					=> $order->get_billing_country(),
		'email'						=> $order->get_billing_email(),
		'phone'						=> $order->get_billing_phone(),
		'emergency_contact_name'	=> $order->get_meta( 'emergencyContactName' ),
		'emergency_contact_phone'	=> $order->get_meta( 'emergencyContactPhone' ),
		'language_1_code'			=> $order->get_meta( 'language1' ),
		'language_1_skill_code'		=> $order->get_meta( 'language1Skill' ),
		'language_2_code'			=> $order->get_meta( 'language2' ),
		'language_2_skill_code'		=> $order->get_meta( 'language2Skill' ),
		'language_3_code'			=> $order->get_meta( 'language3' ),
		'language_3_skill_code'		=> $order->get_meta( 'language3Skill' ),
		'motivation'				=> $order->get_meta( 'motivation' ),
		'health_issues'				=> $order->get_meta( 'healthIssues' ),
		'volunteer_experience'		=> $order->get_meta( 'volunteerExperience' ),
		'together_with'				=> $order->get_meta( 'togetherWith' ),
	);

	$order_data['full_name']		= sprintf( '%s %s', $order_data['first_name'], $order_data['last_name'] );
	$order_data['nationality']		= $nationalities[ $order_data['nationality_code'] ];
	$order_data['address']			= sprintf( '%s %s<br/>%s %s<br/>%s', $order_data['street'] , $order_data['housenumber'], $order_data['postcode'], $order_data['city'], $order_data['country'] );
	$order_data['language_1']		= $languages[ $order_data['language_1_code'] ];
	$order_data['language_1_skill']	= $language_skill[ $order_data['language_1_skill_code'] ];
	$order_data['language_2']		= ! empty( $order_data['language_2_code'] ) ? $languages[ $order_data['language_2_code'] ] : '';
	$order_data['language_2_skill']			= isset( $language_skill[ $order_data['language_2_skill_code'] ] ) ? $language_skill[ $order_data['language_2_skill_code'] ] : '';
	$order_data['language_3']				= ! empty( $order_data['language_3_code'] ) ? $languages[ $order_data['language_3_code'] ] : '';
	$order_data['language_3_skill']			= isset( $language_skill[ $order_data['language_3_skill_code'] ] ) ? $language_skill[ $order_data['language_3_skill_code'] ] : '';

	return $order_data;
}
