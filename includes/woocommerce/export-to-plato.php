<?php
/*
 * (c) 2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Betaalde aanmeldingen exporteren naar PLATO */
add_action( 'woocommerce_order_status_processing', 'siw_export_application_to_plato' );


/**
 * Exporteert aanmelding voor groepsproject naar Plato
 *
 * @param object $order Aanmelding
 *
 * @return void
 */
function siw_export_application_to_plato( $order ) {

	if ( ! is_object( $order ) ) {
		$order = new WC_Order( $order );
	}
	/* Ophalen Plato webkey en url; afbreken als deze niet allebei gevonden worden. */
	$organization_webkey = siw_get_setting( 'plato_organization_webkey' );
	$webservice_url = siw_get_setting( 'plato_webservice_url' );

	if ( '' == $organization_webkey || '' == $webservice_url ) {
		$order->add_order_note( 'Instellingen voor export naar PLATO ontbreken. Neem contact op met ICT-beheer.' );
		return;
	}

	// Export van aanmelding gebruikt endpoint ImportVolunteer
	$import_volunteer_webservice_url = $webservice_url . '/ImportVolunteer';

	// Zet HTTP-post argumenten
	$args = array(
			'timeout'		=> 60,
			'redirection'	=> 0,
			'httpversion'	=> '1.0',
			'sslverify'		=> true,
			'blocking'		=> true,
			'headers'		=> array(
				'accept'		=> 'application/xml',
				'content-type'	=> 'application/x-www-form-urlencoded'
			),
			'cookies'		=> array(),
			'user-agent'	=> 'siw.nl',
		);

	/* Haal velden voor aanmelding op */
	$application_fields = siw_get_application_fields_for_xml( $order );

	$failed_count = 0;
	$success_count = 0;

	/* Elk project per aanmelding apart exporteren. */
	foreach( $order->get_items() as $item_id => $item_data ) {
		/* Bepaal projectcode */
		$product = $order->get_product_from_item( $item_data );
		$projectcode = $product->get_sku();
		$application_fields['choice1'] = $projectcode;

		/* xml opbouwen */
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><vef></vef>");
		foreach ( $application_fields as $key => $value ) {
			$xml->addChild("$key","$value");
		}
		$xml_data = $xml->asXML();

		/* Bouw bericht voor webservice op */
		$args['body'] = 'organizationWebserviceKey=' . $organization_webkey . '&xmlData=' . rawurlencode( $xml_data );

		/* Roep webservice aan */
		$response = wp_safe_remote_post( $import_volunteer_webservice_url, $args );

		/* In het geval van een fout: foutmelding wegschrijven naar log */
		if ( is_wp_error( $response ) ) {
			$order->add_order_note( 'Er is een fout opgetreden bij de export naar PLATO. Neem contact op met ICT-beheer' );
			siw_log( $response );
			$failed_count++;
			break;
		}

		/* Zoek HTML-statuscode en breek af indien ongelijk aan 200 */
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( '200' != $status_code ) {
			$order->add_order_note( 'Verbinding met PLATO mislukt. Neem contact op met ICT-beheer.' );
			$failed_count++;
			break;
		}

		$body = simplexml_load_string( wp_remote_retrieve_body( $response ) );
		$success = (string) $body->Success;
		if ( 'true' == $success ) {
			/* Bug in PLATO: imported_id geeft organizationWebserviceKey terug i.p.v. application_id */
			//$imported_id = (string) $body->ImportedIds->string;
			$note = sprintf( 'Aanmelding voor %s succesvol geëxporteerd naar PLATO.', $projectcode );
			$order->add_order_note( $note );
			$success_count++;
		}
		else {
			/* foutmeldingen tonen bij order notes */
			$error_messages = $body->ErrorMessages->string;
			$note = sprintf( 'Export naar PLATO van aanmelding voor %s mislukt.', $projectcode );
			foreach ( $error_messages as $message ) {
				$note .= '<br />-' . (string) $message;
			}
			$order->add_order_note( $note );
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
	$outgoing_placements_officer = siw_get_setting( 'plato_export_outgoing_placements_name' );
	$outgoing_placements_email = siw_get_setting( 'plato_export_outgoing_placements_email' );
	$order_data = siw_get_order_data( $order );

	return array(
		'firstname'			=> $order_data['first_name'],
		'lastname'			=> $order_data['last_name'],
		'sex' 				=> $order_data['gender_code'],
		'birthdate'			=> date( 'Y-m-d', strtotime( $order_data['date_of_birth'] ) ),
		'email' 			=> $order_data['email'],
		'nationality'		=> $order_data['nationality_code'],
		'telephone'			=> $order_data['phone'],
		'address1'			=> sprintf( '%s %s', $order_data['street'], $order_data['housenumber'] ),
		'zip'				=> $order_data['postcode'] ,
		'city'				=> $order_data['city'] ,
		'country'			=> 'NLD', //TODO: uit order halen
		'occupation'		=> 'OTH', //TODO: uitvragen?
		'emergency_contact'	=> sprintf( '%s %s', $order_data['emergency_contact_name'], $order_data['emergency_contact_phone'] ),
		'language1'			=> $order_data['language_1_code'],
		'language2'			=> $order_data['language_2_code'],
		'language3'			=> $order_data['language_3_code'],
		'langlevel1'		=> $order_data['language_1_skill_code'],
		'langlevel2'		=> $order_data['language_2_skill_code'],
		'langlevel3'		=> $order_data['language_3_skill_code'],
		'special_needs'		=> $order_data['health_issues'],
		'experience'		=> $order_data['volunteer_experience'],
		'motivation'		=> $order_data['motivation']	,
		'together_with'		=> $order_data['together_with']	,
		'req_sent_by'		=> $outgoing_placements_officer,
		'req_sender_email'	=> $outgoing_placements_email,
		'date_filed'		=> date( 'Y-m-d' ),
	);
}
