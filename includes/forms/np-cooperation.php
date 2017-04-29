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
	$forms['samenwerking_np'] = apply_filters( 'caldera_forms_get_form-samenwerking_np', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-samenwerking_np', function( $form ) {

	$signature = siw_get_setting( 'np_camp_leader_email_signature' );


	/* TODO: verplaatsen naar instellingen*/
	$confirmation_template_args['subject'] = 'Bevestiging interesse samenwerking';
	$confirmation_template_args['message'] = 'Beste %naam_contactpersoon%,<br/><br/>';
	$confirmation_template_args['message'] .= 'Wat leuk dat u interesse heeft in een samenwerking met SIW Internationale Vrijwilligersprojecten! Wij willen u bedanken voor het achterlaten van uw contactgegevens en wensen. Ons streven is binnen drie tot vijf werkdagen contact met u op te nemen om de mogelijkheden te bespreken. ';
	$confirmation_template_args['show_summary'] = true;
	$confirmation_template_args['show_signature'] = true;
	$confirmation_template_args['signature_name'] = $signature['name'];
	$confirmation_template_args['signature_title'] = $signature['title'];
	$confirmation_template_args['remove_linebreaks'] = true;


	$notification_template_args['subject'] = 'Interesse samenwerking';
	$notification_template_args['message'] = 'Via de website is onderstaand bericht verstuurd:';
	$notification_template_args['show_summary'] = true;
	$notification_template_args['show_signature'] = false;


return array(
	'ID'			=> 'samenwerking_np',
	'name'			=> __('Samenwerking NP', 'siw'),
	//'description'	=> __('TODO:', 'siw'),
	'db_support'	=> 0,
 	'pinned'		=> 0,
	'pin_roles'		=>
	array(
		'access_role'	=>
		array(
			'editor'	=> 1,
			//'regiospecialist' => 1,
		),
	),
	'hide_form'			=> 1,
	'check_honey'		=> 1,
	'success'			=> __( 'Uw bericht werd succesvol verzonden.', 'siw' ),
	'avatar_field'		=> '',
	'form_ajax'			=> 1,
	'has_ajax_callback'	=> 1,
	'custom_callback'	=> 'siwSendGaFormSubmissionEvent',
	'layout_grid'		=> array(
		'fields' => array(
			'intro' => '1:1',
			'naam_organisatie' => '1:1',
			'naam_contactpersoon' => '1:1',
			'emailadres' => '1:1',
			'telefoonnummer' => '1:1',
			'toelichting' => '1:1',
			'bekend' => '1:1',
			'bekend_anders' => '1:1',
			'submit' => '1:1',
		),
		'structure' => '12',
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
				'default' => 'Bent u enthousiast geworden? SIW is het hele jaar door op zoek naar enthousiaste maatschappelijke organisaties die samen met ons willen onderzoeken wat de mogelijkheden zijn voor een samenwerking. Laat uw gegevens achter in onderstaand formulier en wij nemen contact met u op om de mogelijkheden te bespreken.<hr>',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'naam_organisatie' =>
		array(
			'ID' => 'naam_organisatie',
			'type' => 'text',
			'label' => 'Naam organisatie',
			'slug' => 'naam_organisatie',
			'required' => '1',
			'caption' => '',
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
		'naam_contactpersoon' =>
		array(
			'ID' => 'naam_contactpersoon',
			'type' => 'text',
			'label' => 'Naam contactpersoon',
			'slug' => 'naam_contactpersoon',
			'required' => '1',
			'caption' => '',
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
			'label' => 'E-mailadres',
			'slug' => 'emailadres',
			'required' => '1',
			'caption' => '',
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
			'label' => 'Telefoonnummer',
			'slug' => 'telefoonnummer',
			'required' => '1',
			'caption' => '',
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
		'toelichting' =>
		array(
			'ID' => 'toelichting',
			'type' => 'paragraph',
			'label' => __('Beschrijf kort op welke manier u wilt samenwerken met SIW', 'siw'),
			'slug' => 'toelichting',
			'required' => '1',
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
		'bekend' =>
		array(
			'ID' => 'bekend',
			'type' => 'checkbox',
			'label' => __( 'Hoe heeft u van SIW gehoord?', 'siw' ),
			'slug' => 'bekend',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'default' => '',
				'option' =>
				array(
					'google' =>
					array(
						'value' => 'google',
						'label' => __( 'Google', 'siw' ),
					),
					'social_media' =>
					array(
						'value' => 'social_media',
						'label' => __( 'Social Media', 'siw' ),
					),
					'infodag' =>
					array(
						'value' => 'infodag',
						'label' => __( 'SIW Infodag', 'siw' ),
					),
					'anders' =>
					array(
						'value' => 'anders',
						'label' => __( 'Anders', 'siw' ),
					),
				),
			),
	    ),
		'bekend_anders' =>
		array(
			'ID' => 'bekend_anders',
			'type' => 'text',
			'label' => __( 'Namelijk', 'siw' ),
			'hide_label' => 1,
			'slug' => 'bekend_anders',
			'required' => 1,
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => '',
				'default' => '',
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => 'con_bekend_anders',
			),
		),
		'submit' =>
		array(
			'ID' => 'submit',
			'type' => 'button',
			'label' => __( 'Verzenden', 'siw' ),
			'slug' => 'submit',
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
				'sender_email' => siw_get_setting( 'np_cooperation_email_sender' ),
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
			'con_bekend_anders' =>
			array(
				'id' => 'con_bekend_anders',
				'name' => 'Bekend anders',
				'type' => 'show',
				'group' =>
				array(
					'con_bekend_anders_group_1' =>
					array(
						'con_bekend_anders_group_1_line_1' =>
						array(
							'parent' => 'con_bekend_anders_group_1',
							'field' => 'bekend',
							'compare' => 'is',
							'value' => 'anders',
						),
					),
				),
			),
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
		'sender_email' => siw_get_setting( 'np_cooperation_email_sender' ),
		'reply_to' => '%emailadres%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'np_cooperation_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
		'csv_data' => 0,
	),
);
} );
