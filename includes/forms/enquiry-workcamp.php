<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Filter admin forms to include custom form in admin
 *
 * @since 1.3.1
 *
 * @param array $forms All registered forms
 */
add_filter( 'caldera_forms_get_forms', function( $forms ){
	$forms['contact_project'] = apply_filters( 'caldera_forms_get_form-contact_project', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-contact_project', function( $form ) {


	$signature = siw_get_setting( 'enquiry_workcamp_email_signature' );
	/*E-mail bevestiging*/
	$confirmation_template_args = array(
		'subject' => __( 'Bevestiging informatieverzoek', 'siw' ),
		'message' =>
			sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
			sprintf( __( 'Leuk om te zien dat je interesse hebt getoond in het project %s.', 'siw' ), '<a href="{embed_post:permalink}" target="_blank" style="text-decoration:none">{embed_post:post_title}<a/>') . SPACE .
			__( 'Je hebt ons een vraag gesteld.', 'siw' ) . SPACE .
			__( 'Wellicht was er iets niet helemaal duidelijk of wil je graag meer informatie ontvangen.', 'siw' ) . SPACE .
			__( 'Wat de reden ook was, wij helpen je graag verder.', 'siw' ) . SPACE .
			__( 'We nemen zo snel mogelijk contact met je op.', 'siw' ),
		'show_signature' => true,
		'signature_name' => $signature['name'],
		'signature_title' => $signature['title'],
		'show_summary' => true,
	);

	/*E-mail notificatie*/
	$notification_template_args = array(
		'subject' => sprintf( __( 'Informatieverzoek project %s', 'siw'),  '{embed_post:post_title}' ),
		'message' =>
			sprintf( __( 'Via de website is een vraag gesteld over het project %s', 'siw' ), '{embed_post:post_title} (<a href="{embed_post:permalink}" target="_blank" style="text-decoration:none">{embed_post:permalink}<a/>)<br/>' ),
		'show_summary' => true,
	);

return array(
	'ID'				=> 'contact_project',
	'name'				=> __( 'Infoverzoek Groepsproject', 'siw' ),
	'db_support'		=> 0,
 	'pinned'			=> 0,
	'hide_form'			=> 1,
	'check_honey'		=> 1,
	'success'			=> __( 'Je bericht werd succesvol verzonden.', 'siw'),
	'form_ajax'			=> 1,
	'scroll_top'		=> 1,
	'has_ajax_callback'	=> 1,
	'custom_callback'	=> 'siwSendGaFormSubmissionEvent',
	'layout_grid'		=> array(
		'fields' => array(
			'intro' => '1:1',
			'intro_hr' => '1:1',
			'voornaam' => '2:1',
			'achternaam' => '2:2',
			'emailadres' => '3:1',
			'telefoonnummer' => '3:2',
			'vraag' => '4:1',
			'verzenden' => '4:1',
		),
		'structure' => '12|6:6|6:6|12',
	),
	'fields' =>
	array(
		'intro' => siw_get_form_field( 'html', array(
			'ID' => 'intro',
			'sllug' => 'intro',
			'config' => array(
				'default' =>
					__( 'Heb je een vraag over dit project?', 'siw' ) . SPACE .
					__( 'Neem gerust contact met ons op.', 'siw' ) . SPACE .
					__( 'We staan voor je klaar en denken graag met jou mee!', 'siw' ),
				),
			)
		),
		'intro_hr' => siw_get_standard_form_field( 'intro_hr' ),
		'voornaam' => siw_get_standard_form_field( 'voornaam' ),
		'achternaam' => siw_get_standard_form_field( 'achternaam' ),
		'emailadres' => siw_get_standard_form_field( 'emailadres' ),
		'telefoonnummer' => siw_get_standard_form_field( 'telefoonnummer', array(
			'required' => 0,
			)
		),
		'vraag' => siw_get_standard_form_field( 'vraag' ),
		'verzenden' => siw_get_standard_form_field( 'verzenden' ),
	),
	'page_names' =>
	array(
		0 => 'Page 1',
	),
	'processors' =>
	array(
		'fp_autoresponder' =>
		array(
			'ID' => 'fp_autoresponder',
			'type' => 'auto_responder',
			'config' =>
			array(
				'sender_name' => SIW_Properties::NAME,
				'sender_email' => siw_get_setting( 'enquiry_workcamp_email_sender' ),
				'subject' => $confirmation_template_args['subject'],
				'recipient_name' => '%voornaam% %achternaam%',
				'recipient_email' => '%emailadres%',
				'message' => siw_get_email_template( $confirmation_template_args ),
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
	),
	'conditional_groups' =>
	array(),
	'settings' =>
	array(
		'responsive' =>
		array(
			'break_point' => 'sm',
		),
	),
	'mailer' =>
	array(
		'on_insert' => 1,
		'sender_name' => __( 'Website', 'siw' ),
		'sender_email' => siw_get_setting( 'enquiry_workcamp_email_sender' ),
		'reply_to' => '%emailadres%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'enquiry_workcamp_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
	),
);
} );
