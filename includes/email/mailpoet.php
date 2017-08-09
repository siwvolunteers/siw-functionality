<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_update_plugin', function () {
	if ( ! class_exists( 'WYSIJA' ) ) {
		return;
	}
	$model_config = WYSIJA::get('config','model');
	$confirm_email_id = $model_config->values['confirm_email_id'];

	//$signature = siw_get_setting( 'newsletter_confirmation_email_signature' );
	$signature['name'] = __( 'De vrijwilligers van SIW', 'siw' );
	$signature['title'] = '';
	/*E-mail bevestiging*/
	$template_args['subject'] = __( 'Aanmelding nieuwsbrief', 'siw' );
	$template_args['message'] =
		sprintf( __( 'Beste %s,', 'siw' ), '[user:firstname]' ) . BR2 .
		__( 'Bedankt voor je aanmelding voor de SIW-nieuwsbrief!', 'siw' ) . SPACE .
		__( 'Om zeker te weten dat je inschrijving correct is, vragen we je je aanmelding te bevestigen.', 'siw' ) . BR2 .
		'[activation_link]' .
		__( 'Klik hier om je aanmelding voor onze nieuwsbrief direct te bevestigen', 'siw' ) .
		'[/activation_link]' . BR2 .
		sprintf( __( 'Tip: voeg %s toe aan je adresboek.', 'siw' ), SIW_EMAIL ) . SPACE .
		__( 'Zo mis je nooit meer nieuws over onze infodagen, ervaringsverhalen of projecten.', 'siw' );

	$template_args['show_signature'] = true;
	$template_args['signature_name'] = $signature['name'];
	$template_args['signature_title'] = $signature['title'];
	$template_args['remove_linebreaks'] = true;

	$template = siw_get_email_template( $template_args );

	global $wpdb;
	if ( ! isset( $wpdb->wysija_email ) ) {
		$wpdb->wysija_email = $wpdb->prefix . 'wysija_email';
	}
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE $wpdb->wysija_email
			SET body = %s
				WHERE $wpdb->wysija_email.email_id  = %d",
			$template,
			$confirm_email_id
        )
	);
});
