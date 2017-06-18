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
		<td width="5%"></td>
		<td width="50%"></td>
	</tr>
<?php
}


/**
 * Toon projectdetails voor bevestingsmail WooCommerce
 * @param object $order
 * @param string $aanmeldnummer
 *
 * @return void
 */
function siw_wc_email_show_project_details( $order, $application_number ) {

	//TODO: ophalen gegevens verplaatsen naar getters
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	siw_wc_generate_email_table_header_row( __( 'Aanmelding', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Aanmeldnummer', 'siw' ), $application_number );

	//TODO: beter formatteren
	foreach ( $order->get_items() as $item_id => $item ) {
		$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
		$item_meta    = new WC_Order_Item_Meta( $item, $_product );


		//projectdetails formatteren
		$item_details = apply_filters( 'woocommerce_order_item_name', $item['name'], $item ) . ' ( ' . get_post_meta( $_product->id, 'projectduur', true) . ' )';
		if ( $item_meta->meta ) {
			$item_details .= '<br/><small>' . nl2br( $item_meta->display( true, true, '_', "\n" ) ) . '</small>';
		}
		siw_wc_generate_email_table_row( __( 'Project', 'siw') , $item_details);
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
	siw_wc_generate_email_table_row( __( 'Betaalwijze', 'siw' ), $order->payment_method_title );
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

	//TODO:Ophalen gegevens verplaatsen naar getters

	//hulplijstjes
	$genders = siw_get_volunteer_genders();
	$nationalities = siw_get_volunteer_nationalities();
	$languages = siw_get_volunteer_languages();
	$language_skill = siw_get_volunteer_language_skill_levels();

	//naam, gegeboortedatum, geslacht en nationaliteit
	$first_name = $order->billing_first_name;
	$last_name = $order->billing_last_name;
	$full_name = $first_name . ' ' . $last_name;
	$date_of_birth = $order->billing_dob;
	$gender = $genders[ $order->billing_gender ];
	$nationality = $nationalities[ $order->billing_nationality ];

	//adres formatteren
	$address = $order->billing_address_1 . ' ' . $order->billing_housenumber . '<br/>' . $order->billing_postcode . ' ' . $order->billing_city . '<br/>' . $order->billing_country;
	$email = $order->billing_email;
	$phone = $order->billing_phone;

	//gegevens noodcontact
	$emergency_contact_name = get_post_meta( $order->id, 'emergencyContactName', true );
	$emergency_contact_phone = get_post_meta( $order->id, 'emergencyContactPhone', true );

	//talenkennis
	$language_1 = $languages[get_post_meta( $order->id, 'language1', true )];
	$language_1_skill = $language_skill[ get_post_meta( $order->id, 'language1Skill', true ) ];

	$language_2_code = get_post_meta( $order->id, 'language2', true );
	$language_2 = ! empty( $language_2_code ) ? $languages[ $language_2_code ] : '';
	$language_2_skill_code = get_post_meta( $order->id, 'language2Skill', true );
	$language_2_skill = ! empty( $language_2_skill_code ) ? $language_skill[ $language_2_skill_code ] : '';

	$language_3_code = get_post_meta( $order->id, 'language3', true );
	$language_3 = ! empty( $language_3_code ) ? $languages[ $language_3_code ] : '';
	$language_3_skill_code = get_post_meta( $order->id, 'language3Skill', true );
	$language_3_skill = ! empty( $language_3_skill_code ) ? $language_skill[ $language_3_skill_code ] : '';

	//gegevens voor PO
	$motivation = get_post_meta( $order->id, 'motivation', true );
	$health_issues = get_post_meta( $order->id, 'healthIssues', true );
	$volunteer_experience = get_post_meta( $order->id, 'volunteerExperience', true );
	$together_with = get_post_meta( $order->id, 'togetherWith', true );
	?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	//Persoonsgegevens
	siw_wc_generate_email_table_header_row( __( 'Persoonsgegevens', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Naam', 'siw' ), $full_name );
	siw_wc_generate_email_table_row( __( 'Geboortedatum', 'siw' ), $date_of_birth );
	siw_wc_generate_email_table_row( __( 'Geslacht', 'siw' ), $gender );
	siw_wc_generate_email_table_row( __( 'Nationaliteit', 'siw' ), $nationality );
	siw_wc_generate_email_table_row( __( 'Adres', 'siw' ), $address );
	siw_wc_generate_email_table_row( __( 'E-mailadres', 'siw' ), $email );
	siw_wc_generate_email_table_row( __( 'Telefoonnummer', 'siw' ), $phone );

	//gegevens noodcontact
	siw_wc_generate_email_table_header_row( __( 'Noodcontact', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Naam', 'siw' ), $emergency_contact_name );
	siw_wc_generate_email_table_row( __( 'Telefoonnummer', 'siw' ), $emergency_contact_phone );

	//talenkennis
	siw_wc_generate_email_table_header_row( __( 'Talenkennis', 'siw' ) );
	siw_wc_generate_email_table_row( $language_1, $language_1_skill );
	if ( $language_2_code ) {
		siw_wc_generate_email_table_row( $language_2, $language_2_skill );
	}
	if ( $language_3_code ) {
		siw_wc_generate_email_table_row( $language_3, $language_3_skill );
	}

	//gegevens voor PO
	siw_wc_generate_email_table_header_row( __( 'Informatie voor partnerorganisatie', 'siw' ) );
	siw_wc_generate_email_table_row( __( 'Motivation', 'siw' ), $motivation );
	if ( $health_issues ) {
		siw_wc_generate_email_table_row( __( 'Health issues', 'siw' ), $health_issues );
	}
	if ( $volunteer_experience ) {
		siw_wc_generate_email_table_row( __( 'Volunteer experience', 'siw' ), $volunteer_experience );
	}
	if ( $together_with ) {
		siw_wc_generate_email_table_row( __( 'Together with', 'siw' ), $together_with );
	}
	?>
	</table>
<?php
}
