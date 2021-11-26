<?php
/**
 * Customer on-hold order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-on-hold-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;
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

		if ( $order->has_status( 'on-hold' ) ) {
			esc_html_e( 'Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
			esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
			esc_html_e( 'Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.', 'siw' ); echo BR2;
			printf( esc_html__( 'Je kunt je betaling overmaken naar %s o.v.v. je aanmeldnummer (%s).', 'siw' ), Properties::IBAN, $order->get_order_number() ); echo BR;
		}
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
