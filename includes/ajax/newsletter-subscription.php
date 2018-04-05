<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Aanmelding voor nieuwsbrief via AJAX */
siw_register_ajax_action( 'newsletter_subscription' );

add_action( 'siw_ajax_newsletter_subscription', function() {
	if ( ! class_exists( 'WYSIJA' ) ) {
		return;
	}
	check_ajax_referer( 'siw_newsletter_nonce', 'security' );

	$name = $_POST['name'];
	$email = $_POST['email'];
	$list =  $_POST['list'];


	$mailpoet_lists = siw_get_mailpoet_lists();

	if ( ! $name || ! is_email( $email ) || ! array_key_exists( $list, $mailpoet_lists ) ) {
		$data = array(
			'message'	=> __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		);
		wp_send_json_error( $data );
	}

	/* Aanmeldingen voor specifieke domeinen blokkeren*/
	$blocked_domains = array( 'siw.nl' ); //TODO:filter
	$domain = substr( $email, strrpos( $email, '@' ) + 1 );

	if( in_array( $domain, $blocked_domains ) ) {
		$data = array(
			'message'	=> sprintf( __( 'Het is niet mogelijk om je aan te melden met een @%s adres.', 'siw' ), esc_html( $domain ) ),
		);
		wp_send_json_error( $data );	
	}


	/* Meerdere aanmelding van zelfde IP-adres binnen X uur blokkeren*/
	$helperUser = WYSIJA::get( 'user', 'helper' );
	if( ! $helperUser->throttleRepeatedSubscriptions() ) {
		$data = array(
			'message'	=> __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		);
		wp_send_json_error( $data );
	}

	$user_data = array(
		'firstname'	=> sanitize_text_field( $name ),
		'email'		=> sanitize_email( $email ),
	);
	$data_subscriber = array(
		'user'		=> $user_data,
		'user_list'	=> array(
			'list_ids' => array(
				intval( $list ),
			),
		),
	);

	$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber );
	if ( is_numeric( $user_id ) ) {
		$data = array(
			'message'	=> __( 'Je bent er bijna! Check je inbox voor de bevestigingsmail om je aanmelding voor de nieuwsbrief te bevestigen.', 'siw' ),
		);
		wp_send_json_success( $data );
	}
	elseif ( $user_id ) {
		$data = array(
			'message'	=> __( 'Je bent al ingeschreven.', 'siw' ),
		);
		wp_send_json_success( $data );
	}
	else {
		$data = array(
			'message'	=> __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		);
		wp_send_json_error( $data );
	}

} );
