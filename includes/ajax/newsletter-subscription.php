<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Aanmelding voor nieuwsbrief via AJAX */
add_filter( 'siw_ajax_allowed_actions', function( $actions ) {
	$actions[] = 'newsletter_subscription';
	return $actions;
} );

add_action( 'siw_ajax_newsletter_subscription', function() {
	if ( ! class_exists( 'WYSIJA' ) ) {
		return;
	}
	check_ajax_referer( 'siw-newsletter-nonce', 'security' );

	$name = $_POST['name'];
	$email = $_POST['email'];
	$list = (int) $_POST['list'];

	if ( $name && is_email( $email ) && $list ) {
		$user_data = array(
			'firstname'	=> sanitize_text_field( $name ),
			'email'		=> sanitize_email( $email ),
		);
		$data_subscriber = array(
			'user'		=> $user_data,
			'user_list'	=> array(
				'list_ids' => array(
					$list,
				),
			),
		);

		$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber );
		if ( is_numeric( $user_id ) ) {
			$data = array(
				'success'	=> 1,
				'message'	=> __( 'Je bent er bijna! Check je inbox voor de bevestigingsmail om je aanmelding voor de nieuwsbrief te bevestigen.', 'siw' ),
			);
		}
		elseif ( $user_id ) {
			$data = array(
				'success'	=> 1,
				'message'	=> __( 'Je bent al ingeschreven.', 'siw' ),
			);
		}
		else{
			$data = array(
				'success'	=> 0,
				'message'	=> __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
			);
		}
	}
	else{
		$data = array(
			'success'	=> 0,
			'message'	=> __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		);
	}

$result = json_encode( $data );
echo $result;
die();
} );
