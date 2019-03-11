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
	$forms['begeleider_np'] = apply_filters( 'caldera_forms_get_form-begeleider_np', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-begeleider_np', function( $form ) {

	$signature = siw_get_setting( 'np_camp_leader_email_signature' );
	/*E-mail bevestiging*/
	//TODO: tekst conditioneel van datum maken
	$confirmation_template_args = array(
		'subject' => __( 'Bevestiging aanmelding', 'siw' ),
		'message' =>
			sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
			__( 'Bedankt voor jouw aanmelding.', 'siw') . SPACE .
			__( 'Wat leuk dat je interesse hebt om projectbegeleider te worden voor de Nederlandse vrijwilligersprojecten.', 'siw' ) . SPACE .
			__( 'Een creatieve uitdaging die je nooit meer zal vergeten!', 'siw' ) . SPACE .
			__( 'Zoals oud-projectbegeleider Diederik (project in Friesland) het omschreef:', 'siw' ) . BR .
			'<span style="font-style:italic">"'.
			__( 'Het is ontzettend leerzaam om met zoveel verschillende mensen om te gaan, iedereen gemotiveerd te houden en te zorgen dat iedereen zich op zijn gemak voelt.', 'siw' ) . SPACE .
			__( 'Daarnaast zie je hoe de groep zich ontwikkelt, een prachtig proces om van zo dichtbij mee te mogen maken.', 'siw' ) .
			'"</span>' . BR2 .
			'<span style="font-weight:bold">' .
			__( 'Hoe gaat het nu verder?', 'siw' ) .
			'</span>' . BR .
			__( 'Wij werven doorgaans in de maanden maart tot en met mei projectbegeleiders om de zomerprojecten te begeleiden.', 'siw' ) . SPACE .
			__( 'Mocht jij je in deze periode hebben aangemeld, dan zullen wij contact met je opnemen.', 'siw' ) . SPACE .
			__( 'Ligt jouw aanmelding buiten onze wervingsperiode? Geen probleem.', 'siw' ) . SPACE .
			__( 'Wij voegen jouw aanmelding toe aan onze database voor een volgend zomerseizoen.', 'siw' ),
		'show_signature' => true,
		'signature_name' => $signature['name'],
		'signature_title' => $signature['title'],
		'show_summary' => true,
	);
	/*E-mail notificatie*/
	$notification_template_args = array(
		'subject' => 'Aanmelding projectbegeleider',
		'message' => 'Via de website is onderstaande aanmelding voor begeleider NP binnengekomen:',
		'show_summary' => true,
	);

	$language = SIW_i18n::get_current_language();
	$project_options = [];
	$projects = siw_get_option( 'dutch_projects' );
	foreach ( $projects as $project ) {
		$slug = sanitize_title( $project['code'] );
		$name = $project["name_{$language}"];

		$project_options[ $slug ] = [
			'value' => $slug,
			'label' => $name,
		];
	}

return array(
	'ID'				=> 'begeleider_np',
	'name'				=> __( 'Projectbegeleider NP', 'siw' ),
	'db_support'		=> 0,
 	'pinned'			=> 0,
	'hide_form'			=> 1,
	'check_honey'		=> 1,
	'success'			=> __( 'Je bericht werd succesvol verzonden.', 'siw' ),
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
			'emailadres' => '4:1',
			'telefoonnummer' => '4:2',
			'postcode' => '5:1',
			'huisnummer' => '5:2',
			'straat' => '6:1',
			'woonplaats' => '6:2',
			'motivatie' => '7:1',
			'voorkeur' => '7:2',
			'bekend' => '8:1',
			'bekend_anders' => '8:1',
			'opmerkingen' => '8:2',
			'verzenden' => '9:1',
		),
		'structure' => '12|6:6|6:6|6:6|6:6|6:6|6:6|6:6|12',
	),
	'fields' =>
	array(
		'intro' => siw_get_form_field( 'html', array(
			'ID' => 'intro',
			'slug' => 'intro',
			'config' => array(
				'default' =>
					__( 'De Nederlandse vrijwilligersprojecten vinden plaats in de zomermaanden.', 'siw') . SPACE .
					__( 'We zijn hiervoor altijd op zoek naar projectbegeleiders.', 'siw') . SPACE .
					__( 'Meld je aan via onderstaand formulier en geef aan naar welk project je voorkeur uitgaat.', 'siw') . SPACE .
					__( 'Vervolgens ontvang je een uitnodiging voor een kennismakingsgesprek.', 'siw') . SPACE .
					__( 'In dit gesprek hopen wij erachter te komen wat je verwachtingen en kwaliteiten zijn.', 'siw'),
				),
			)
		),
		'intro_hr' => siw_get_standard_form_field( 'intro_hr'),
		'voornaam' => siw_get_standard_form_field( 'voornaam' ),
		'achternaam' => siw_get_standard_form_field( 'achternaam' ),
		'geboortedatum' => siw_get_standard_form_field( 'geboortedatum' ),
		'geslacht' => siw_get_standard_form_field( 'geslacht' ),
		'emailadres' => siw_get_standard_form_field( 'emailadres' ),
		'telefoonnummer' => siw_get_standard_form_field( 'telefoonnummer' ),
		'postcode' => siw_get_standard_form_field( 'postcode' ),
		'huisnummer' => siw_get_standard_form_field( 'huisnummer' ),
		'straat' => siw_get_standard_form_field( 'straat' ),
		'woonplaats' => siw_get_standard_form_field( 'woonplaats' ),
		'motivatie' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'motivatie',
				'label' => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
				'slug' => 'motivatie',
			)
		),
		'voorkeur' =>
		array(
			'ID' => 'voorkeur',
			'type' => 'checkbox',
			'label' => __( 'Heb je een voorkeur om een bepaald Nederlands vrijwilligersproject te begeleiden?', 'siw' ),
			'slug' => 'voorkeur',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'default' => '',
				'option' => $project_options
			),
	    ),
		'bekend' => siw_get_standard_form_field( 'bekend' ),
		'bekend_anders' => siw_get_standard_form_field( 'bekend_anders' ),
		'opmerkingen' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'opmerkingen',
				'label' => __( 'Overige opmerkingen?', 'siw' ),
				'slug' => 'opmerkingen',
				'required' => 0,
			)
		),
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
				'sender_email' => siw_get_setting( 'np_camp_leader_email_sender' ),
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
		'sender_email' => siw_get_setting( 'np_camp_leader_email_sender' ),
		'reply_to' => '%email%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'np_camp_leader_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
	),
);
} );
