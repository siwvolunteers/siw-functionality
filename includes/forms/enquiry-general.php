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
	$forms['contact_algemeen'] = apply_filters( 'caldera_forms_get_form-contact_algemeen', array() );
	return $forms;
} );


/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-contact_algemeen', function( $form ) {
	$signature = siw_get_setting( 'enquiry_general_email_signature' );
	/*E-mail bevestiging*/
	$confirmation_template_args['subject'] = __( 'Bevestiging informatieverzoek', 'siw' );
	$confirmation_template_args['message'] =
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor het invullen van ons contactformulier.', 'siw' ) . SPACE .
		__( 'Wij hebben je vraag ontvangen en we nemen zo snel mogelijk contact met je op.', 'siw' );

	$confirmation_template_args['show_signature'] = true;
	$confirmation_template_args['signature_name'] = $signature['name'];
	$confirmation_template_args['signature_title'] = $signature['title'];
	$confirmation_template_args['show_summary'] = true;

	/*E-mail notificatie*/
	$notification_template_args['subject'] = 'Informatieverzoek %voornaam% %achternaam%';
	$notification_template_args['message'] = 'Via de website is een vraag gesteld:' . BR;

	$notification_template_args['show_signature'] = false;
	$notification_template_args['show_summary'] = true;
	$confirmation_template_args['remove_linebreaks'] = true;


return array(
	'ID'			=> 'contact_algemeen',
	'name'			=> __( 'Infoverzoek algemeen', 'siw' ),
	'db_support'	=> 0,
 	'pinned'		=> 0,
	'pin_roles'		=>
	array(
		'access_role'	=>
		array(
			'publiciteit'	=> 1,
		),
	),
	'hide_form'			=> 1,
	'check_honey'		=> 1,
	'success'			=> __( 'Je bericht werd succesvol verzonden.', 'siw' ),
	'form_ajax'			=> 1,
	'scroll_top'		=> 1,
	'has_ajax_callback'	=> 1,
	'custom_callback'	=> 'siwSendGaFormSubmissionEvent',
	'layout_grid'		=> 	array(
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
		'intro' =>
		array(
			'ID' => 'intro',
			'type' => 'html',
			'label' => 'header',
			'slug' => 'header',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'default' =>
					__( 'Heb je een vraag of wil je graag meer informatie?', 'siw' ) . SPACE .
					__( 'Neem gerust contact met ons op.', 'siw' ) . SPACE .
					__( 'We staan voor je klaar en denken graag met jou mee!', 'siw' ),
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'intro_hr' =>
		array(
			'ID' => 'intro_hr',
			'type' => 'section_break',
			'label' => 'intro_hr',
			'slug' => 'intro_hr',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'width' => '',
			),
		),
		'voornaam' =>
		array(
			'ID' => 'voornaam',
			'type' => 'text',
			'label' => __( 'Voornaam', 'siw' ),
			'slug' => 'voornaam',
			'required' => 1,
			'caption' => '',
			'entry_list' => 1,
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'achternaam' =>
		array(
			'ID' => 'achternaam',
			'type' => 'text',
			'label' => __( 'Achternaam', 'siw' ),
			'slug' => 'achternaam',
			'required' => 1,
			'caption' => '',
			'entry_list' => 1,
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'emailadres' =>
		array(
			'ID' => 'emailadres',
			'type' => 'email',
			'label' => __( 'E-mailadres', 'siw' ),
			'slug' => 'emailadres',
			'required' => 1,
			'caption' => '',
			'entry_list' => 1,
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'telefoonnummer' =>
		array(
			'ID' => 'telefoonnummer',
			'type' => 'text',
			'label' => __( 'Telefoonnummer', 'siw' ),
			'slug' => 'telefoonnummer',
			'required' => 0,
			'caption' => '',
			'entry_list' => 1,
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => '',
				'type_override' => 'tel',
				'default' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'vraag' =>
		array(
			'ID' => 'vraag',
			'type' => 'paragraph',
			'label' => __( 'Vraag', 'siw' ),
			'slug' => 'vraag',
			'required' => 1,
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => '',
				'rows' => '7',
				'default' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'verzenden' =>
		array(
			'ID' => 'verzenden',
			'type' => 'button',
			'label' => __( 'Verzenden', 'siw' ),
			'slug' => 'verzenden',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'type' => 'submit',
				'class' => 'kad-btn kad-btn-primary',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
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
				'sender_name' => SIW_NAME,
				'sender_email' => siw_get_setting( 'enquiry_general_email_sender' ),
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
		'sender_email' => siw_get_setting( 'enquiry_general_email_sender' ),
		'reply_to' => '%emailadres%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'enquiry_general_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
	),
);

});
