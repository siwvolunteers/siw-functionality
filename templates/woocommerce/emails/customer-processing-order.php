<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.5.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Util\CSS;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

$email_settings = siw_get_email_settings( 'workcamp' );

?>
<div style="font-family:Verdana, normal; color:<?= CSS::CONTRAST_COLOR;?>; font-size:0.9em; ">
	<p>
		<?php
		printf( esc_html__( 'Beste %s,', 'siw'), $order->get_billing_first_name() ); echo BR2;

		if ( 'mollie_wc_gateway_ideal' == $order->get_payment_method() ) {
			esc_html_e( 'Heel erg bedankt voor je aanmelding en betaling voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
			esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
		}
		elseif ( 'cod' == $order->get_payment_method() ) {
			esc_html_e( 'Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
			esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
			esc_html_e( 'In verband met het wereldwijde coronavirus is de doorgang van het door jou gekozen project onzeker.', 'siw' ); echo SPACE;
			esc_html_e( 'Daarom brengen we nu nog geen inschrijfkosten in rekening.', 'siw' ); echo SPACE;
			esc_html_e( 'Je ontvangt van ons pas een factuur als je plaatsing definitief is.', 'siw' ); echo BR2;
		}
		else {
			esc_html_e( 'Heel erg bedankt voor je betaling.', 'siw' ); echo BR2;
		}
		esc_html_e( 'We gaan je aanmelding doorzetten naar onze partnerorganisatie en nemen contact met je op zodra we bericht hebben ontvangen over je plaatsing.', 'siw' ); echo BR;
		esc_html_e( 'Gemiddeld duurt het 5 werkdagen om een aanmelding voor een project binnen Europa te verwerken.', 'siw' ); echo SPACE;
		esc_html_e( 'Voor een projectaanmelding buiten Europa duurt het ongeveer 2 weken voor je van ons hoort of je definitief geplaatst bent.', 'siw' ); echo BR2;
		if ( 'cod' != $order->get_payment_method() ) {
			esc_html_e( 'We willen je er nadrukkelijk op wijzen dat deze email nog geen bevestiging is van deelname, maar een bevestiging van ontvangst van je betaling.', 'siw' ); echo SPACE;
		}
		esc_html_e( 'Boek nog geen tickets, totdat je van ons ook een bevestiging hebt ontvangen van je plaatsing.', 'siw' ); echo SPACE;
		esc_html_e( 'Het kan zijn dat in de tussentijd het maximale deelnemersaantal op het project is bereikt.', 'siw' ); echo BR2;

		esc_html_e( 'Als je nog vragen hebt, aarzel dan niet om contact met ons op te nemen.', 'siw'); echo BR2;
		esc_html_e( 'Met vriendelijke groet,', 'siw' ); echo BR2;
		echo esc_html( $email_settings['name'] )
		?>
		<br/>
		<span style="color:#808080"><?php echo esc_html( $email_settings['title'] )?> </span>
	</p>
</div>

<?php

/**
 * @hooked SIW_WC_Emails::order_table()
 */
do_action( 'siw_woocommerce_email_order_table', $order );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );