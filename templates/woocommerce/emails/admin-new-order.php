<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails/HTML
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;
use SIW\HTML;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );

if ( $order->has_status( 'processing' ) ) {
	$application_status = __( 'inclusief betaling', 'siw' );
}
else {
	$application_status = __( 'nog niet betaald', 'siw' );
}
$admin_link_url = admin_url( sprintf('post.php?post=%s&action=edit', $order->get_id() ) );
$admin_link_text = sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );

?>
<div style="font-family:Verdana, normal; color:<?= Properties::FONT_COLOR;?>; font-size:14px; ">
	<p>
		<?php
		printf( esc_html__( 'Er is een nieuwe aanmelding (%s) binnengekomen:', 'siw' ),  $application_status ); echo BR;
		echo HTML::generate_link( $admin_link_url, $admin_link_text );
		?>
	</p>
</div>
<?php

/**
 * @hooked SIW_WC_Emails::order_table()
 */
do_action( 'siw_woocommerce_email_order_table', $order );

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email ); ?>
