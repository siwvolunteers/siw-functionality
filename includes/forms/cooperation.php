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
add_filter( 'caldera_forms_get_forms', function( $forms ) {
	$forms['samenwerking'] = apply_filters( 'caldera_forms_get_form-samenwerking', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-samenwerking', function( $form ) {

	$signature = siw_get_option( 'cooperation_email_signature' );

	$confirmation_template_args = array(
		'subject' => __( 'Bevestiging interesse samenwerking', 'siw' ),
		'message' =>
			sprintf( __( 'Beste %s,', 'siw' ), '%naam_contactpersoon%' ) . BR2 .
			__( 'Wat leuk dat u interesse heeft in een samenwerking met SIW Internationale Vrijwilligersprojecten!', 'siw' ) . SPACE .
			__( 'Wij willen u bedanken voor het achterlaten van uw contactgegevens en wensen.', 'siw' ) . SPACE .
			__( 'Ons streven is binnen drie tot vijf werkdagen contact met u op te nemen om de mogelijkheden te bespreken.', 'siw' ),
		'show_signature' => true,
		'signature_name' => $signature['name'],
		'signature_title' => $signature['title'],
		'show_summary' => true,
	);


	$notification_template_args = array(
		'subject' => 'Interesse samenwerking',
		'message' => 'Via de website is onderstaand bericht verstuurd:',
		'show_summary' => true,
	);


return array(
	'ID'			=> 'samenwerking',
	'name'			=> __( 'Samenwerking', 'siw' ),
	'db_support'	=> 0,
 	'pinned'		=> 0,
	'pin_roles'		=>
	array(
		'access_role'	=>
		array(
			'editor'	=> 1,
		),
	),
	'hide_form'			=> 1,
	'check_honey'		=> 1,
	'success'			=> __( 'Uw bericht werd succesvol verzonden.', 'siw' ),
	'form_ajax'			=> 1,
	'scroll_top'		=> 1,
	'has_ajax_callback'	=> 1,
	'custom_callback'	=> 'siwSendGaFormSubmissionEvent',
	'layout_grid'		=> array(
		'fields' => array(
			'intro' => '1:1',
			'intro_hr' => '1:1',
			'naam_organisatie' => '1:1',
			'naam_contactpersoon' => '1:1',
			'emailadres' => '1:1',
			'telefoonnummer' => '1:1',
			'toelichting' => '1:1',
			'bekend' => '1:1',
			'bekend_anders' => '1:1',
			'verzenden' => '1:1',
		),
		'structure' => '12',
	),
	'fields' =>
	array(
		'intro' => siw_get_form_field( 'html', array(
			'ID' => 'intro',
			'slug' => 'intro',
			'config' => array(
				'default' =>
					__( 'Bent u enthousiast geworden?', 'siw' ) . SPACE .
					__( 'SIW is het hele jaar door op zoek naar enthousiaste maatschappelijke organisaties die samen met ons willen onderzoeken wat de mogelijkheden zijn voor een samenwerking.', 'siw' ) . SPACE .
					__( 'Laat uw gegevens achter in onderstaand formulier en wij nemen contact met u op om de mogelijkheden te bespreken.', 'siw' ),
				),
			)
		),
		'intro_hr' => siw_get_standard_form_field( 'intro_hr'),
		'naam_organisatie' => siw_get_form_field( 'text',
			array(
				'ID' => 'naam_organisatie',
				'label' => __( 'Naam organisatie', 'siw' ),
				'slug' => 'naam_organisatie',
			)
		),
		'naam_contactpersoon' => siw_get_form_field( 'text',
			array(
				'ID' => 'naam_contactpersoon',
				'label' => __( 'Naam contactpersoon', 'siw' ),
				'slug' => 'naam_contactpersoon',
			)
		),
		'emailadres' => siw_get_standard_form_field( 'emailadres' ),
		'telefoonnummer' => siw_get_standard_form_field( 'telefoonnummer' ),
		'toelichting' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'toelichting',
				'label' => __( 'Beschrijf kort op welke manier u wilt samenwerken met SIW', 'siw' ),
				'slug' => 'toelichting',
			)
		),
		'bekend' => siw_get_standard_form_field( 'bekend' ),
		'bekend_anders' => siw_get_standard_form_field( 'bekend_anders' ),
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
				'sender_email' => siw_get_option( 'cooperation_email_sender' ),
				'subject' => $confirmation_template_args['subject'],
				'recipient_name' => '%naam_organisatie%',
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
	array(
		'conditions' =>
		array(
			'con_bekend_anders' => siw_get_standard_form_condition( 'con_bekend_anders' ),
		),
	),
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
		'sender_email' => siw_get_option( 'cooperation_email_sender' ),
		'reply_to' => '%emailadres%',
		'email_type' => 'html',
		'recipients' => siw_get_option( 'cooperation_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
		'csv_data' => 0,
	),
);
} );
