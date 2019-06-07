<?php
/*
 * (c) 2017-2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Betaalde aanmeldingen exporteren naar PLATO */
add_action( 'woocommerce_order_status_processing', 'siw_export_application_to_plato' );


/**
 * Exporteert aanmelding voor Groepsproject naar Plato
 *
 * @param object $order Aanmelding
 *
 * @return void
 */
function siw_export_application_to_plato( $order ) {

	if ( ! is_object( $order ) ) {
		$order = new WC_Order( $order );
	}

	/* Haal velden voor aanmelding op */
	$application_data = siw_get_application_fields_for_xml( $order );

	$failed_count = 0;
	$success_count = 0;

	/* Elk project per aanmelding apart exporteren. */
	foreach ( $order->get_items() as $item_id => $item_data ) {
		$product = $order->get_product_from_item( $item_data );
		$projectcode = $product->get_sku();
		$application_data['choice1'] = $projectcode;

		$export = new SIW_Plato_Export_Application;
		$result = $export->run( $application_data );

		$order->add_order_note( $result['message'] );

		if ( true == $result['success'] ) {
			$success_count++;
		}
		else {
			$failed_count++;
		}
	}

	/* Resultaat opslaan bij aanmelding */
	if ( 0 != $failed_count ) {
		$order->update_meta_data( '_exported_to_plato', 'failed' );
		$order->save();
	}
	elseif ( 0 != $success_count ) {
		$order->update_meta_data( '_exported_to_plato', 'success' );
		$order->save();
	}
}


/**
 * Genereert array met gegevens aanmelding voor export-xml
 *
 * @param object $order Aanmelding
 *
 * @return array
 */
function siw_get_application_fields_for_xml( $order ) {
	/*Ophalen instellingen en ordergegevens*/
	$outgoing_placements_officer = siw_get_option( 'plato_export_outgoing_placements_name' );
	$outgoing_placements_email = siw_get_option( 'plato_export_outgoing_placements_email' );
	$order_data = siw_get_order_data( $order );

	return [
		'firstname'         => $order_data['first_name'],
		'lastname'          => $order_data['last_name'],
		'sex'               => $order_data['gender_code'],
		'birthdate'         => date( 'Y-m-d', strtotime( $order_data['date_of_birth'] ) ),
		'email'             => $order_data['email'],
		'nationality'       => $order_data['nationality_code'],
		'telephone'         => $order_data['phone'],
		'address1'          => sprintf( '%s %s', $order_data['street'], $order_data['housenumber'] ),
		'zip'               => $order_data['postcode'] ,
		'city'              => $order_data['city'] ,
		'country'           => 'NLD', //TODO: uit order halen
		'occupation'        => 'OTH', //TODO: uitvragen?
		'emergency_contact' => sprintf( '%s %s', $order_data['emergency_contact_name'], $order_data['emergency_contact_phone'] ),
		'language1'         => $order_data['language_1_code'],
		'language2'         => $order_data['language_2_code'],
		'language3'         => $order_data['language_3_code'],
		'langlevel1'        => $order_data['language_1_skill_code'],
		'langlevel2'        => $order_data['language_2_skill_code'],
		'langlevel3'        => $order_data['language_3_skill_code'],
		'special_needs'     => $order_data['health_issues'],
		'experience'        => $order_data['volunteer_experience'],
		'motivation'        => $order_data['motivation']	,
		'together_with'     => $order_data['together_with']	,
		'req_sent_by'       => $outgoing_placements_officer,
		'req_sender_email'  => $outgoing_placements_email,
		'date_filed'        => date( 'Y-m-d' ),
	];
}
