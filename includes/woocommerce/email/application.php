<?php
/*
(c)2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* Stuur mail sturen bij statusovergang van 'Aangemeld' naar 'Betaald' */
add_action( 'woocommerce_email', function( $email_class ) {
	add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
});


/**
 * Genereer tabelrij voor WooCommerce e-mail
 * @param string $name
 * @param string $value
 *
 * @return void
 */
function siw_wc_generate_email_table_row( $name, $value = '&nbsp;' ) {?>
	<tr>
		<td width="35%" style="font-family:Verdana, normal; color:#444; font-size:0.8em; ">
			<?php echo esc_html( $name ); ?>
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
function siw_wc_generate_email_table_header_row( $name ) {?>
	<tr>
		<td width="35%" style="font-family:Verdana, normal; color:#444; font-size:0.8em; font-weight:bold">
			<?php echo esc_html( $name ); ?>
		</td>
		<td width="5%">&nbsp;</td>
		<td width="50%">&nbsp;</td>
	</tr>
<?php
}


/**
 * Toon projectdetails voor bevestingsmail WooCommerce
 * @param object $order
 * @param string $application_number
 *
 * @return void
 */
function siw_wc_email_show_project_details( $order, $application_number ) {

	//TODO: ophalen gegevens verplaatsen naar getters
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="3" height="20" style="font-family:Verdana, normal; color:#666; font-size:0.8em; font-weight:bold; border-top:thin solid #ff9900" >
				&nbsp;
			</td>
		</tr>
	<?php
	siw_wc_generate_email_table_header_row( __( 'Aanmelding', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Aanmeldnummer', 'siw' ), $application_number );

	foreach ( $order->get_items() as $item_id => $item ) {

		$parent_id = $item->get_product_id();
		$parent = wc_get_product( $parent_id );

		$project_name = $parent->get_name();
		$project_code = $parent->get_sku();
		$start_date = $parent->get_attribute( 'startdatum' );
		$end_date = $parent->get_attribute( 'einddatum' );
		$project_duration = siw_get_date_range_in_text( $start_date, $end_date, false );
		$tariff = $item['pa_tarief'];
		$project_details = sprintf('%s (%s)<br/><small>%s | Tarief:%s</small>', $project_name, $project_code, $project_duration, $tariff );

		siw_wc_generate_email_table_row( __( 'Project', 'siw' ) , $project_details);
	}

	$discount = $order->get_total_discount();
	$subtotal = $order->get_subtotal();
	$total = $order->get_total();

	//subtotaal alleen tonen als het afwijkt van het totaal
	if ( $subtotal != $total ) {
		siw_wc_generate_email_table_row( __( 'Subtotaal', 'siw' ), $order->get_subtotal_to_display() );
		siw_wc_generate_email_table_row( __( 'Korting', 'siw'), '-' . $order->get_discount_to_display() );
	}
	siw_wc_generate_email_table_row( __( 'Totaal', 'siw' ), $order->get_formatted_order_total() );
	siw_wc_generate_email_table_row( __( 'Betaalwijze', 'siw' ), $order->get_payment_method_title() );
	?>
	</table>
	<?php
}


/**
 * Toon aanmeldingsgegevens voor bevestingsmail WooCommerce
 * @param object $order
 *
 * @return void
 */
function siw_wc_email_show_application_details ( $order ) {
	$order_data = siw_get_order_data( $order );
	?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	//Persoonsgegevens
	siw_wc_generate_email_table_header_row( __( 'Persoonsgegevens', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Naam', 'siw' ), $order_data['full_name'] );
	siw_wc_generate_email_table_row( __( 'Geboortedatum', 'siw' ), $order_data['date_of_birth'] );
	siw_wc_generate_email_table_row( __( 'Geslacht', 'siw' ), $order_data['gender'] );
	siw_wc_generate_email_table_row( __( 'Nationaliteit', 'siw' ), $order_data['nationality'] );
	siw_wc_generate_email_table_row( __( 'Adres', 'siw' ), $order_data['address'] );
	siw_wc_generate_email_table_row( __( 'E-mailadres', 'siw' ), $order_data['email'] );
	siw_wc_generate_email_table_row( __( 'Telefoonnummer', 'siw' ), $order_data['phone'] );

	//gegevens noodcontact
	siw_wc_generate_email_table_header_row( __( 'Noodcontact', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Naam', 'siw' ), $order_data['emergency_contact_name'] );
	siw_wc_generate_email_table_row( __( 'Telefoonnummer', 'siw' ), $order_data['emergency_contact_phone'] );

	//talenkennis
	siw_wc_generate_email_table_header_row( __( 'Talenkennis', 'siw' ) );
	siw_wc_generate_email_table_row( $order_data['language_1'], $order_data['language_1_skill'] );
	if ( ! empty( $order_data['language_2'] ) ) {
		siw_wc_generate_email_table_row( $order_data['language_2'], $order_data['language_2_skill'] );
	}
	if ( ! empty( $order_data['language_3'] ) ) {
		siw_wc_generate_email_table_row( $order_data['language_3'], $order_data['language_3_skill'] );
	}

	//gegevens voor PO
	siw_wc_generate_email_table_header_row( __( 'Informatie voor partnerorganisatie', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Motivation', 'siw' ), $order_data['motivation'] );
	if ( ! empty( $order_data['health_issues'] ) ) {
		siw_wc_generate_email_table_row( __( 'Health issues', 'siw' ), $order_data['health_issues'] );
	}
	if ( ! empty( $order_data['volunteer_experience'] ) ) {
		siw_wc_generate_email_table_row( __( 'Volunteer experience', 'siw' ), $order_data['volunteer_experience'] );
	}
	if ( ! empty( $order_data['together_with'] ) ) {
		siw_wc_generate_email_table_row( __( 'Together with', 'siw' ), $order_data['together_with'] );
	}
	?>
	</table>
<?php
}
