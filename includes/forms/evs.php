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
	$forms['evs'] = apply_filters( 'caldera_forms_get_form-evs', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-evs', function( $form ) {

	$signature = siw_get_setting( 'evs_email_signature' );
	/*E-mail bevestiging*/
	$confirmation_template_args = array(
		'subject' => __( 'Bevestiging aanmelding', 'siw' ),
		'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor je EVS-aanmelding.', 'siw' ) . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'We nemen zo snel mogelijk contact met je op om in een gesprek verder met je kennis te maken en op zoek te gaan naar een leuk en geschikt project!', 'siw' ),
		'show_signature' => true,
		'signature_name' => $signature['name'],
		'signature_title' => $signature['title'],
		'show_summary' => true,
		'remove_linebreaks' => true,
	);
	/*E-mail notificatie*/
	$notification_template_args = array(
		'subject' => 'Aanmelding EVS',
		'message' => 'Via de website is onderstaande EVS-aanmelding binnengekomen:',
		'show_signature' => false,
		'show_summary' => true,
	);


	/* Referentiegegevens TODO: hergebruiken voor Op maat?*/
	$duration = array(
		'2-5' =>
		array(
			'value' => '2-5',
			'label' => __( '2-5 maanden', 'siw' ),
		),
		'6-9' =>
		array(
			'value' => '6-9',
			'label' => __( '6-9 maanden', 'siw' ),
		),
		'10-12' =>
		array(
			'value' => '10-12',
			'label' => __( '10-12 maanden', 'siw' ),
		),
	);


return array(
	'ID'			=> 'evs',
	'name'			=> __( 'EVS', 'siw' ),
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
			'geboortedatum' => '3:1',
			'geslacht' => '3:2',
			'telefoonnummer' => '4:1',
			'emailadres' => '4:2',
			'postcode' => '5:1',
			'huisnummer' => '5:2',
			'straat' => '6:1',
			'woonplaats' => '6:2',
			'motivatie' => '7:1',
			'startmoment' => '7:2',
			'projectduur' => '7:2',
			'werk' => '8:1',
			'bestemming' => '8:2',
			'cv' => '9:1',
			'bekend' => '9:2',
			'bekend_anders' => '9:2',
			'verzenden' => '10:1',
		),
		'structure' => '12|6:6|6:6|6:6|6:6|6:6|6:6|6:6|6:6|12',
	),
	'fields' =>
	array(
		'intro' => siw_get_form_field( 'html', array(
			'ID' => 'intro',
			'slug' => 'intro',
			'config' => array(
				'default' =>
					__( 'Start snel jouw eigen EVS avontuur!', 'siw' ) . SPACE .
					__( 'Als je onderstaand formulier invult nemen wij zo snel mogelijk contact met je op.', 'siw' ),
				),
			)
		),
		'intro_hr' => siw_get_standard_form_field( 'intro_hr'),
		'voornaam' => siw_get_standard_form_field( 'voornaam' ),
		'achternaam' => siw_get_standard_form_field( 'achternaam' ),
		'geboortedatum' => siw_get_standard_form_field( 'geboortedatum' ),
		'geslacht' => siw_get_standard_form_field( 'geslacht' ),
		'cv' => siw_get_standard_form_field( 'cv' ),
		'emailadres' => siw_get_standard_form_field( 'emailadres' ),
		'telefoonnummer' => siw_get_standard_form_field( 'telefoonnummer' ),
		'postcode' => siw_get_standard_form_field( 'postcode' ),
		'huisnummer' => siw_get_standard_form_field( 'huisnummer' ),
		'straat' => siw_get_standard_form_field( 'straat' ),
		'woonplaats' => siw_get_standard_form_field( 'woonplaats' ),
		'motivatie' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'motivatie',
				'label' => __( 'Waarom wil je graag aan een EVS-project deelnemen?', 'siw' ),
				'slug' => 'motivatie',
			)
		),
		'startmoment' => siw_get_form_field( 'text',
			array(
				'ID' => 'startmoment',
				'label' => __( 'Wanneer wil met je EVS-project starten?', 'siw' ),
				'slug' => 'startmoment',
			)
		),
		'projectduur' => siw_get_form_field( 'radio',
			array(
				'ID' => 'projectduur',
				'label' => __( 'Hoelang wil je weg?', 'siw' ),
				'slug' => 'projectduur',
				'config' =>
				array(
					'option' => $duration,
				),
			)
		),
		'werk' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'werk',
				'label' => __( 'Wat voor soort werk wil je doen?', 'siw' ),
				'slug' => 'werk',
				'config' =>
				array(
					'rows' => 4,
				),
			)
		),
		'bestemming' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'bestemming',
				'label' => __( 'Heb je een voorkeur voor een land of regio?', 'siw' ),
				'slug' => 'bestemming',
				'config' =>
				array(
					'rows' => 4,
				),
			)
		),
		'bekend' => siw_get_form_field( 'checkbox',
 			array(
				'ID' => 'bekend',
				'label' => __( 'Hoe heb je van EVS (bij SIW) gehoord?', 'siw' ),
				'slug' => 'bekend',
				'config' =>
				array(
					'option' =>
					array(
						'google' =>
						array(
							'value' => 'google',
							'label' => __( 'Google', 'siw' ),
						),
						'website' =>
						array(
							'value' => 'website',
							'label' => __( 'Website SIW', 'siw' ),
						),
						'social_media' =>
						array(
							'value' => 'social_media',
							'label' => __( 'Social Media', 'siw' ),
						),
						'familie_vrienden' =>
						array(
							'value' => 'familie_vrienden',
							'label' => __( 'Familie / vrienden', 'siw' ),
						),
						'infodag' =>
						array(
							'value' => 'infodag',
							'label' => __( 'SIW Infodag', 'siw' ),
						),
						'nji' =>
						array(
							'value' => 'nji',
							'label' => __( 'NJI EVS info middag/avond', 'siw' ),
						),
						'anders' =>
						array(
							'value' => 'anders',
							'label' => __( 'Anders', 'siw' ),
						),
					),
				),
			)
		),
		'bekend_anders' => siw_get_standard_form_field( 'bekend_anders' ),
		'verzenden' =>  siw_get_standard_form_field( 'verzenden' ),
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
				'sender_email' => siw_get_setting( 'evs_email_sender' ),
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
		'sender_email' => siw_get_setting( 'evs_email_sender' ),
		'reply_to' => '%email%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'evs_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
		'csv_data' => 0,
	),
);
} );
