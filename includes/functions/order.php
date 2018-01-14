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

	/* Naam, gegeboortedatum, geslacht en nationaliteit */
	$order_data['first_name']		= $order->get_billing_first_name();
	$order_data['last_name']		= $order->get_billing_last_name();
	$order_data['full_name']		= sprintf( '%s %s', $order_data['first_name'], $order_data['last_name'] );
	$order_data['date_of_birth']	= $order->get_meta( '_billing_dob' );
	$order_data['gender_code']		= $order->get_meta( '_billing_gender' );
	$order_data['gender']			= $genders[ $order_data['gender_code'] ];
	$order_data['nationality_code']	= $order->get_meta( '_billing_nationality' );
	$order_data['nationality']		= $nationalities[ $order_data['nationality_code'] ];
	$order_data['street'] 			= $order->get_billing_address_1();
	$order_data['housenumber'] 		= $order->get_meta( '_billing_housenumber' );
	$order_data['postcode'] 		= $order->get_billing_postcode();
	$order_data['city'] 			= $order->get_billing_city();
	$order_data['country']			= $order->get_billing_country();
	/* Adres formatteren voor e-mail */
	$order_data['address']			= sprintf( '%s %s<br/>%s %s<br/>%s', $order_data['street'] , $order_data['housenumber'], $order_data['postcode'], $order_data['city'], $order_data['country'] );
	$order_data['email']			= $order->get_billing_email();
	$order_data['phone']			= $order->get_billing_phone();

	/* Gegevens noodcontact */
	$order_data['emergency_contact_name']	= $order->get_meta( 'emergencyContactName' );
	$order_data['emergency_contact_phone']	= $order->get_meta( 'emergencyContactPhone' );

	/* Talenkennis */
	$order_data['language_1_code']			= $order->get_meta( 'language1' );
	$order_data['language_1']				= $languages[ $order_data['language_1_code'] ];
	$order_data['language_1_skill_code']	= $order->get_meta( 'language1Skill' );
	$order_data['language_1_skill']			= $language_skill[ $order_data['language_1_skill_code'] ];

	$order_data['language_2_code']			= $order->get_meta( 'language2' );
	$order_data['language_2']				= ! empty( $order_data['language_2_code'] ) ? $languages[ $order_data['language_2_code'] ] : '';
	$order_data['language_2_skill_code']	= $order->get_meta( 'language2Skill' );
	$order_data['language_2_skill']			= isset( $language_skill[ $order_data['language_2_skill_code'] ] ) ? $language_skill[ $order_data['language_2_skill_code'] ] : '';

	$order_data['language_3_code']			= $order->get_meta( 'language3' );
	$order_data['language_3']				= ! empty( $order_data['language_3_code'] ) ? $languages[ $order_data['language_3_code'] ] : '';
	$order_data['language_3_skill_code']	= $order->get_meta( 'language3Skill' );
	$order_data['language_3_skill']			= isset( $language_skill[ $order_data['language_3_skill_code'] ] ) ? $language_skill[ $order_data['language_3_skill_code'] ] : '';

	/* Gegevens voor partner */
	$order_data['motivation']			= $order->get_meta( 'motivation' );
	$order_data['health_issues']		= $order->get_meta( 'healthIssues' );
	$order_data['volunteer_experience']	= $order->get_meta( 'volunteerExperience' );
	$order_data['together_with']		= $order->get_meta( 'togetherWith' );

	return $order_data;
}
