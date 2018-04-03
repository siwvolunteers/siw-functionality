<?php
/*
(c)2017-2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* Stuur mail sturen bij statusovergang van 'Aangemeld' naar 'Betaald' */
add_action( 'woocommerce_email', function( $email_class ) {
	add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
});


/* Afzender: naam en e-mailadres*/
add_filter( 'woocommerce_email_from_name', function( $from_name, $email ) {
	$from_name = SIW_NAME;
	return $from_name;
}, 10, 2);

add_filter( 'woocommerce_email_from_address', function( $from_address, $email ) {
	$from_address = siw_get_setting( 'workcamp_application_email_sender' );
	return $from_address;
}, 10, 2 );

/* Ontvanger admin-mail */
add_filter( 'woocommerce_email_recipient_new_order', function( $recipient, $email ) {
	$recipient = siw_get_setting( 'workcamp_application_email_sender' );
	return $recipient;
}, 10, 2 );


/* Bepaal onderwerp
 * - Admin: nieuwe aanmelding
 * - Klant: aangemeld / betaald
 * - Klant: betaald
 */
add_filter( 'woocommerce_email_subject_new_order', function( $subject, $order ) {
	$subject = sprintf( __( 'Nieuwe aanmelding groepsproject (%s)', 'siw' ), $order->get_order_number() );
	return $subject;
}, 10, 2 );

add_filters( array( 'woocommerce_email_subject_customer_on_hold_order', 'woocommerce_email_subject_customer_processing_order' ), function( $subject, $order ) {
	$subject = sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	return $subject;
}, 10, 2 );


/* Bepaal heading
 * - Admin: nieuwe aanmelding
 * - Klant: aangemeld
 * - Klant: betaald
 */
add_filter( 'woocommerce_email_heading_new_order', function( $heading, $order ) {
	if ( $order->has_status( 'processing' ) ) {
		$heading = sprintf( __( 'Nieuwe aanmelding (betaald)', 'siw' ), $order->get_order_number() );
	}
	else {
		$heading = sprintf( __( 'Nieuwe aanmelding (nog niet betaald)', 'siw' ), $order->get_order_number() );
	}

	return $heading;
}, 10, 2 );

add_filter( 'woocommerce_email_heading_customer_on_hold_order', function( $heading, $order ) {
	$heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw'), $order->get_order_number() );
	return $heading;
}, 10, 2 );

add_filters( 'woocommerce_email_heading_customer_processing_order', function( $email_heading, $order ) {
	//bepaal onderwerp
	if ( 'mollie_wc_gateway_ideal' == $order->get_payment_method() ) {
		$heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
	}
	else {
		$heading = sprintf( __( 'Bevestiging betaling aanmelding #%s', 'siw'), $order->get_order_number() );
	}
	return $heading;
}, 10, 2 );


/* Bevestigingsmail voor klant */
add_action( 'siw_woocommerce_email_order_customer', function( $order ) {

	//Bepaal ondertekening
	$signature = siw_get_setting( 'workcamp_application_email_signature' );

	?>
	<div style="font-family:Verdana, normal; color:#444; font-size:0.9em; ">
	<p><?php
	printf( esc_html__( 'Beste %s,', 'siw'), $order->get_billing_first_name() ); echo BR2;

	if ( $order->has_status( 'on-hold' ) ) {
		esc_html_e( 'Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
		esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
		esc_html_e( 'Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.', 'siw' ); echo BR2;
		printf( esc_html__( 'Je kunt je betaling overmaken naar %s o.v.v. je aanmeldnummer (%s).', 'siw' ), SIW_IBAN, $order->get_order_number() ); echo BR;
	}
	if ( $order->has_status( 'processing' ) && ('mollie_wc_gateway_ideal' == $order->get_payment_method() ) ) {
		esc_html_e( 'Heel erg bedankt voor je aanmelding en betaling voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
		esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
	}
	if ( $order->has_status( 'processing' ) && ( 'mollie_wc_gateway_ideal' != $order->get_payment_method() ) ) {
		esc_html_e( 'Heel erg bedankt voor je betaling.', 'siw' ); echo BR2;
	}
	if ( $order->has_status( 'processing' ) ) {
		esc_html_e( 'We gaan je aanmelding doorzetten naar onze partnerorganisatie en nemen contact met je op zodra we bericht hebben ontvangen over je plaatsing.', 'siw' ); echo BR;
		esc_html_e( 'Gemiddeld duurt het 5 werkdagen om een aanmelding voor een project binnen Europa te verwerken.', 'siw' ); echo SPACE;
		esc_html_e( 'Voor een projectaanmelding buiten Europa duurt het ongeveer 2 weken voor je van ons hoort of je definitief geplaatst bent', 'siw' ); echo BR2;
		esc_html_e( 'We willen je er nadrukkelijk op wijzen dat deze email nog geen bevestiging is van deelname, maar een bevestiging van ontvangst van je betaling.', 'siw' ); echo SPACE;
		esc_html_e( 'Boek nog geen tickets, totdat je van ons ook een bevestiging hebt ontvangen van je plaatsing.', 'siw' ); echo SPACE;
		esc_html_e( 'Het kan zijn dat in de tussentijd het maximale deelnemersaantal op het project is bereikt.', 'siw' ); echo BR2;
	}

	esc_html_e( 'Als je nog vragen hebt, aarzel dan niet om contact met ons op te nemen.', 'siw'); echo BR2;
	esc_html_e( 'Met vriendelijke groet,', 'siw' ); echo BR2;
	echo esc_html( $signature['name'] )?><br/>
	<span style="color:#808080"><?php echo esc_html( $signature['title'] )?> </span>
	</p>
	</div>

<?php

});


/* Email voor backoffice */
add_action( 'siw_woocommerce_email_order_admin', function( $order ) {
	//bepaal status
	if ( $order->has_status( 'processing' ) ) {
		$application_status = __( 'inclusief betaling', 'siw' );
	}
	else {
		$application_status = __( 'nog niet betaald', 'siw' );
	}

	$admin_url = admin_url( sprintf('post.php?post=%s&action=edit', $order->get_id() ) );
	?>
	<div style="font-family:Verdana, normal; color:#444; font-size:14px; ">
	<p><?php
	printf( esc_html__( 'Er is een nieuwe aanmelding (%s) binnengekomen:', 'siw' ),  $application_status ); echo BR;
	?>
	<a href="<?php echo esc_url( $admin_url );?>"><?php printf( esc_html__( 'Aanmelding %s', 'siw' ), $order->get_order_number() );?></a>
	</p>
	</div>
<?php

});


/* Toon aanmeldinggegevens */
add_action( 'siw_woocommerce_email_order_table', function( $order ) {

	/* Ophalen order gegevens */
	$order_data = siw_get_order_data( $order );


	$table_data[ __( 'Aanmelding', 'siw' ) ] = array(
		__( 'Aanmeldnummer', 'siw' ) => $order->get_order_number(),
	);
	foreach ( $order->get_items() as $item_id => $item ) {
		$parent = wc_get_product( $item->get_product_id() ); //FIXME:continue als product niet (meer) bestaat.
		if ( false == $parent ) {
			$project_name = $item->get_name();
			$project_details = sprintf('<small>Tarief: %s</small>', wc_get_order_item_meta( $item_id )['pa_tarief'][0] );
		}
		else {
			$project_duration = siw_get_date_range_in_text( $parent->get_attribute( 'startdatum' ), $parent->get_attribute( 'einddatum' ), false );
			$tariff = $item['pa_tarief'];
			$project_name = sprintf('%s<br/><small>Projectcode: %s</small>', $parent->get_name(), $parent->get_sku() );
			$project_details = sprintf('<small>Projectduur: %s<br/>Tarief: %s</small>', $project_duration, $item['pa_tarief'] );			
		}
		$projects[ $project_name ] =  $project_details;
	}

	$table_data[ _n( 'Project', 'Projecten', count( $projects ), 'siw' ) ] = $projects;

	$table_data[ __( 'Betaling', 'siw' ) ] = array(
		__( 'Subtotaal', 'siw' ) => ( $order->get_total() != $order->get_subtotal() ) ? $order->get_subtotal_to_display() : '',
		__( 'Korting', 'siw') => ( 0 < $order->get_total_discount() ) ? '-' . $order->get_discount_to_display() : '',
		__( 'Totaal', 'siw' ) => $order->get_formatted_order_total(),
		__( 'Betaalwijze', 'siw' ) => $order->get_payment_method_title(),
	);
	$table_data[ __( 'Persoonsgegevens', 'siw' ) ] = array(
		__( 'Naam', 'siw' ) => $order_data['full_name'],
		__( 'Geboortedatum', 'siw' ) => $order_data['date_of_birth'],
		__( 'Geslacht', 'siw' ) => $order_data['gender'],
		__( 'Nationaliteit', 'siw' ) => $order_data['nationality'],
		__( 'Adres', 'siw' ) => $order_data['address'],
		__( 'E-mailadres', 'siw' ) => $order_data['email'],
		__( 'Telefoonnummer', 'siw' ) => $order_data['phone'],
	);
	$table_data[ __( 'Noodcontact', 'siw' ) ] = array(
		__( 'Naam', 'siw' ) => $order_data['emergency_contact_name'],
		__( 'Telefoonnummer', 'siw' ) => $order_data['emergency_contact_phone'],
	);
	$table_data[ __( 'Talenkennis', 'siw' ) ] = array(
		$order_data['language_1'] => $order_data['language_1_skill'],
		$order_data['language_2'] => $order_data['language_2_skill'],
		$order_data['language_3'] => $order_data['language_3_skill'],
	);	
	$table_data[ __( 'Informatie voor partnerorganisatie', 'siw' ) ] = array(
		__( 'Motivation', 'siw' ) => $order_data['motivation'],
		__( 'Health issues', 'siw' ) => $order_data['health_issues'],
		__( 'Volunteer experience', 'siw' ) => $order_data['volunteer_experience'],
		__( 'Together with', 'siw' ) => $order_data['together_with'],
	);


	//Tabel genereren
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="3" height="20" style="font-family:Verdana, normal; color:#666; font-size:0.8em; font-weight:bold; border-top:thin solid #ff9900" >
				&nbsp;
			</td>
		</tr>
	<?php
	foreach ( $table_data as $section => $lines ) {
		siw_wc_generate_email_table_header_row( $section );
		foreach ( $lines as $label => $value ){
			if ( ! empty( $label ) && ! empty( $value ) ) {
				siw_wc_generate_email_table_row( $label, $value );
			}
		}
	}
	?>
	</table>
	<?php
});


/**
 * Genereer tabelrij voor WooCommerce e-mail
 * @param string $name
 * @param string $value
 *
 * @return void
 */
function siw_wc_generate_email_table_row( $label, $value = '&nbsp;' ) {?>
	<tr>
		<td width="35%" style="font-family:Verdana, normal; color:#444; font-size:0.8em; ">
			<?php echo wp_kses_post( $label ); ?>
		</td>
		<td width="5%"></td>
		<td width="50%" style="font-family:Verdana, normal; color:#444; font-size:0.8em; font-style:italic">
			<?php echo wp_kses_post( $value ); ?>
		</td>
	</tr>
<?php
}


/**
 * Genereer tabelheader voor WooCommerce e-mail
 * @param string $name
 *
 * @return void
 */
function siw_wc_generate_email_table_header_row( $label ) {?>
	<tr>
		<td width="35%" style="font-family:Verdana, normal; color:#444; font-size:0.8em; font-weight:bold">
			<?php echo esc_html( $label ); ?>
		</td>
		<td width="5%">&nbsp;</td>
		<td width="50%">&nbsp;</td>
	</tr>
<?php
}

