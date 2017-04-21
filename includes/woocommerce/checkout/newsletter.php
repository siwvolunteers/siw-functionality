<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Instelling aan WooCommerce toevoegen om lijst te kiezen voor aanmelding nieuwsbrief tijdens checkout */
add_filter( 'woocommerce_get_settings_checkout', function( $settings ) {
	$mailpoet_lists = siw_get_mailpoet_lists();
	$settings[] = array(
		'title' => __( 'Aanmelden voor nieuwsbrief tijdens checkout', 'siw' ),
		'type'  => 'title',
		'id'    => 'siw_woo_newsletter_options',
	);
	$settings[] = array(
		'title'   => __( 'Lijst', 'siw' ),
		'type'    => 'select',
		'id'      => 'siw_woo_newsletter_list',
		'options' => $mailpoet_lists,
	);
	$settings[] = array(
		'type' => 'sectionend',
		'id'   => 'siw_woo_newsletter_options',
	);
	return $settings;
}, 10 );

/* Checkbox toevoegen aan checkout voor aanmelding op nieuwsbrief */
add_action( 'woocommerce_after_checkout_billing_form', function() {
	$checkout = WC()->checkout();
	woocommerce_form_field( 'newsletter_signup', array(
		'type'			=> 'checkbox',
		'class'			=> array( 'form-row-wide' ),
		'clear'			=> true,
		'label'			=> __( 'Ja, ik wil graag de SIW nieuwsbrief ontvangen', 'siw' ),
		), $checkout->get_value( 'newsletter_signup' )
	);
} );

/* Klant toevoegen aan Mailpoetlijst indien deze gekozen heeft voor de nieuwsbrief */
add_action( 'woocommerce_checkout_order_processed', function( $order_id, $posted_form ) {
	$newsletter_signup = isset( $_POST['newsletter_signup'] ) ? 1 : 0;
	$list = (int) get_option( 'siw_woo_newsletter_list' );
	if ( 1 == $newsletter_signup ) {
		$user_data = array(
			'email'		=> sanitize_text_field( $_POST['billing_email'] ),
			'firstname'	=> sanitize_text_field( $_POST['billing_first_name'] ),
			'lastname'	=> sanitize_email( $_POST['billing_last_name'] ),
		);
		$data_subscriber = array(
			'user'		=> $user_data,
			'user_list'	=> array( 'list_ids' => array( $list ) )
		);
		$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber, true );
	}
}, 2, 10 );
