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
	$forms['infodag'] = apply_filters( 'caldera_forms_get_form-infodag', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-infodag', function( $form ) {

	$signature = siw_get_setting( 'info_day_email_signature' );

	/*E-mail bevestiging*/
	$confirmation_template_args = array(
		'subject' => sprintf( __( 'Aanmelding Infodag %s', 'siw' ), '%datum:label%' ),
		'message' =>
			sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
			sprintf( __( 'Bedankt voor je aanmelding voor de Infodag van %s!', 'siw' ), '%datum:label%' )  . SPACE .
			__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
			__( 'Uiterlijk één week van te voren ontvang je de uitnodiging met de definitieve locatie en tijden.', 'siw' ) . BR2 .
			__( 'Als je nog vragen hebt, neem dan gerust contact met ons op.', 'siw' ),
		'show_signature' => true,
		'signature_name' => $signature['name'],
		'signature_title' => $signature['title'],
		'show_summary' => true,
	);

	/*E-mail notificatie*/
	$notification_template_args = array(
		'subject' => 'Aanmelding Infodag %datum:label%',
		'message' => 'Via de website is onderstaande aanmelding voor de Infodag van %datum:label% binnengekomen:',
		'show_summary' => true,
	);

	/* Keuzes infodag */
	$infodays = siw_get_future_info_days( true, 3 );
	$dates = array();
	foreach ( $infodays as $infoday ) {
		$date_slug = sanitize_title( $infoday );
		$dates[ $date_slug ]['value'] = $date_slug;
		$dates[ $date_slug ]['label'] = $infoday;
	}

	/* Continenten */
	$continents = siw_get_continents();
	foreach ( $continents as $continent_slug => $continent_name ) {
		$destinations[ $continent_slug ]['value'] = $continent_slug;
		$destinations[ $continent_slug ]['label'] = $continent_name;
	}


	/* Interesse*/
	$project_types = array(
		'groepsprojecten' =>
		array(
			'value' => 'groepsprojecten',
			'label' => __( 'Groepsvrijwilligerswerk (2 - 3 weken)', 'siw' ),
		),
		'op_maat' =>
		array(
			'value' => 'op_maat',
			'label' => __( 'Vrijwilligerswerk Op Maat (3 weken tot een jaar)', 'siw' ),
		),
		'evs' =>
		array(
			'value' => 'evs',
			'label' => __( 'EVS (European Voluntary Service)', 'siw' ),
		),
	);


return array(
	'ID'			=> 'infodag',
	'name'			=> __( 'Infodag', 'siw' ),
	'description'	=> __( 'Aanmeldformulier voor de infodag', 'siw' ),
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
	'avatar_field'		=> '',
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
			'datum' => '4:1',
			'soort_project' => '4:2',
			'bestemming' => '5:1',
			'bekend' => '5:2',
			'bekend_anders' => '5:2',
			'verzenden' => '6:1',
		),
		'structure' => '12|6:6|6:6|6:6|6:6|12',
	),
	'fields' =>
	array(
		'intro' => siw_get_form_field( 'html', array(
			'ID' => 'intro',
			'slug' => 'intro',
			'config' => array(
				'default' =>
					__( 'Meld je hieronder aan voor de Infodag.', 'siw' ) . SPACE .
					__( 'Dan zorgen wij ervoor dat je van tevoren het programma en een routebeschrijving ontvangt.', 'siw' ),
				),
			)
		),
		'intro_hr' => siw_get_standard_form_field( 'intro_hr'),
		'voornaam' => siw_get_standard_form_field( 'voornaam' ),
		'achternaam' => siw_get_standard_form_field( 'achternaam' ),
		'emailadres' => siw_get_standard_form_field( 'emailadres' ),
		'telefoonnummer' => siw_get_standard_form_field( 'telefoonnummer' ),
		'datum' => siw_get_form_field( 'radio',
			array(
				'ID' => 'datum',
				'slug' => 'datum',
				'label' => __( 'Naar welke Infodag wil je komen?', 'siw' ),
				'entry_list' => 1,
				'config' =>
				array(
					'inline' => 0,
					'option' => $dates,
				),
			)
		),
		'soort_project' => siw_get_form_field( 'checkbox',
			array(
				'ID' => 'soort_project',
				'slug' => 'soort_project',
				'label' => __( 'Heb je interesse in een bepaald soort project?', 'siw' ),
				'required' => 0,
				'entry_list' => 1,
				'config' =>
				array(
					'option' => $project_types,
				),
			)
		),
		'bestemming' => siw_get_form_field( 'checkbox',
			array(
				'ID' => 'bestemming',
				'slug' => 'bestemming',
				'label' => __( 'Heb je interesse in een bepaalde bestemming?', 'siw' ),
				'required' => 0,
				'entry_list' => 1,
				'config' =>
				array(
					'option' => $destinations,
				),
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
				'sender_name' => SIW_NAME,
				'sender_email' => siw_get_setting( 'info_day_email_sender' ),
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
		'sender_email' => siw_get_setting( 'info_day_email_sender' ),
		'reply_to' => '%email%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'info_day_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
	),
);
} );
