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
	$forms['op_maat'] = apply_filters( 'caldera_forms_get_form-op_maat', array() );
	return $forms;
} );

/**
 * Filter form request to include form structure to be rendered
 *
 * @since 1.3.1
 *
 * @param $form array form structure
 */
add_filter( 'caldera_forms_get_form-op_maat', function( $form ) {

	$volunteer_languages = siw_get_volunteer_languages();
	foreach ( $volunteer_languages as $volunteer_language ) {
		$language_slug = sanitize_title( $volunteer_language );
		$languages[$language_slug]['value'] = $language_slug;
		$languages[$language_slug]['label'] = $volunteer_language;
	}

	$volunteer_language_skill_levels = siw_get_volunteer_language_skill_levels();
	foreach ( $volunteer_language_skill_levels as $volunteer_language_skill_level ) {
		$language_skill_level_slug = sanitize_title( $volunteer_language_skill_level );
		$language_skill_levels[ $language_skill_level_slug ]['value'] = $language_skill_level_slug;
		$language_skill_levels[ $language_skill_level_slug ]['label'] = $volunteer_language_skill_level;
	}

	$info_day_page = siw_get_setting( 'info_day_page' );
	$info_day_page = siw_get_translated_page_id( $info_day_page );
	$info_day_page_link = get_page_link( $info_day_page );

	/*
	 * Ophalen op maat landen
	 */
	$op_maat_countries = siw_get_countries_by_property( 'op_maat', true );
	foreach ( $op_maat_countries as $op_maat_country ) {
		$country_slug = $op_maat_country['slug'];
		$country_name = $op_maat_country['name'];
		$countries[ $country_slug ]['value'] = $country_slug;
		$countries[ $country_slug ]['label'] = $country_name;
	}

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

	$signature = siw_get_setting( 'op_maat_email_signature' );

	/*E-mail bevestiging*/
	$confirmation_template_args = array(
		'subject' => __( 'Aanmelding vrijwilligerswerk op maat', 'siw' ),
		'message' =>
			sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
			__( 'Bedankt voor je aanmelding!', 'siw' ) . SPACE .
 			__( 'Leuk dat je hebt gekozen via SIW een vrijwilligersproject op maat te doen.', 'siw' ) . SPACE .
			__( 'Wij zullen ons best gaan doen om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt.', 'siw' ) . BR2 .
			__( 'Onderaan deze e-mail vind je een overzicht van de gegevens zoals je die op het inschrijfformulier hebt ingevuld.', 'siw' ) . BR2 .
			'<span style="font-weight:bold">' .
			__( 'Wat gaat er nu gebeuren?', 'siw' ) .
			'</span>' . BR .
			__( 'Jouw aanmelding voor vrijwilligerswerk op maat wordt doorgestuurd naar onze SIW-regiospecialisten.', 'siw' ) . SPACE .
			__( 'Vervolgens neemt één van de regiospecialisten contact met je op om een kennismakingsgesprek in te plannen.', 'siw' ) . SPACE .
			__( 'Houd er rekening mee dat SIW met vrijwilligers werkt, waardoor het contact soms iets langer kan duren.', 'siw' ) . BR2 .
			'<span style="font-weight:bold">' .
			__( 'Kennismakingsgesprek', 'siw' ) .
			'</span>' . BR .
			__( 'Tijdens het kennismakingsgesprek gaat onze regiospecialist samen met jou kijken welk project op maat het beste bij jouw wensen en voorkeuren aansluit.', 'siw' ) . SPACE .
			__( 'In dit gesprek komen ook thema’s naar voren zoals interesse in culturen, creativiteit, flexibiliteit, enthousiasme en reis- en vrijwilligerswerkervaring.', 'siw' ) . BR2 .
			'<span style="font-weight:bold">' .
			__( 'Voorbereidingsdag', 'siw' ) .
			'</span>' . BR .
			__( 'Na het kennismakingsgesprek nodigen we je uit voor een voorbereidingsdag.', 'siw' ) . SPACE .
			__( 'Mocht je nog geen keuze hebben gemaakt voor een project, dan kan de voorbereiding je helpen in het bepalen wat jij belangrijk vindt.', 'siw' ) . SPACE .
			__( 'Tijdens de voorbereiding krijg je informatie over de continenten, landen, cultuurverschillen en gezondheidszorg.', 'siw' ) . SPACE .
			__( 'Ook wordt er stilgestaan bij jouw verwachtingen, praktische projectsituatie en het zelfstandig verblijven in het buitenland.', 'siw' ) . SPACE .
			__( 'Tijdens de voorbereiding zullen gastsprekers en oud-deelnemers aanwezig zijn.', 'siw' ) . BR2 .
			'<span style="font-weight:bold">' .
			__( 'Meer informatie', 'siw' ) .
			'</span>' . BR .
			sprintf( __( 'Als je nog vragen hebt, aarzel dan niet om contact op te nemen met ons kantoor via %s of via het nummer %s.', 'siw' ), SIW_EMAIL, SIW_PHONE ),
		'show_signature' => true,
		'signature_name' => $signature['name'],
		'signature_title' => $signature['title'],
		'show_summary' => true,
		'remove_linebreaks' => true,
	);

	/*E-mail notificatie*/
	$notification_template_args = array(
		'subject' => __( 'Aanmelding vrijwilligerswerk op maat', 'siw' ),
		'message' => __( 'Via de website is onderstaande aanmelding voor vrijwilligerswerk op maat binnengekomen:', 'siw' ),
		'show_summary' => true,
	);


return array(
	'ID'			=> 'op_maat',
	'name'			=> __( 'Op Maat', 'siw' ),
	'description'	=> __( 'Aanmeldformulier voor Op Maat', 'siw' ),
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
			'naar_scherm_2' => '7:2',
			'motivatie' => '8:1',
			'bestemming' => '9:1',
			'periode' => '10:1',
			'cv' => '11:1',
			'terug_naar_scherm_1' => '12:1',
			'naar_scherm_3' => '12:2',
			'taal_1' => '13:1',
			'taal_1_niveau' => '13:2',
			'taal_2' => '14:1',
			'taal_2_niveau' => '14:2',
			'taal_3' => '15:1',
			'taal_3_niveau' => '15:2',
			'terug_naar_scherm_2' => '16:1',
			'verzenden' => '16:2',
		),
		'structure' => '12|6:6|6:6|6:6|6:6|6:6|6:6#12|12|12|12|6:6#6:6|6:6|6:6|6:6',
	),
	'fields' =>
	array(
		'intro' => siw_get_form_field( 'html', array(
			'ID' => 'intro',
			'slug' => 'intro',
			'config' => array(
				'default' =>
					__( 'Interesse in een project op maat?', 'siw' ) . SPACE .
					__( 'Meld je dan aan via onderstaand formulier.', 'siw' ) . SPACE .
					__( 'Vervolgens zal één van onze regiospecialisten contact met je opnemen voor een kennismakingsgesprek.', 'siw' ) . SPACE .
					sprintf( __( 'Weet je nog niet precies waar je naar toe wil, meld je dan aan voor één van onze <a href="%s">Infodagen</a> en laat je inspireren.', 'siw' ), $info_day_page_link )
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
		'naar_scherm_2' =>
		array(
			'ID' => 'naar_scherm_2',
			'type' => 'button',
			'label' => __( 'Volgende', 'siw'),
			'slug' => 'naar_scherm_2',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'type' => 'next',
				'class' => 'kad-btn kad-btn-primary',
				'target' => '',
			),
		),
		'motivatie' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'motivatie',
				'label' => __( 'Waarom zou je graag vrijwilligerswerk willen doen?', 'siw' ),
				'slug' => 'motivatie',
			)
		),
		'bestemming' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'bestemming',
				'label' => __( 'In welk land of welke regio zou je graag vrijwilligerswerk willen doen?', 'siw' ),
				'slug' => 'bestemming',
			)
		),
		'periode' => siw_get_form_field( 'paragraph',
			array(
				'ID' => 'periode',
				'label' => __( 'In welke periode zou je op avontuur willen?', 'siw' ),
				'slug' => 'periode',
			)
		),
		'cv' => siw_get_standard_form_field( 'cv' ),
		'terug_naar_scherm_1' =>
		array(
			'ID' => 'terug_naar_scherm_1',
			'type' => 'button',
			'label' => __( 'Vorige', 'siw' ),
			'slug' => 'terug_naar_scherm_1',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'type' => 'prev',
				'class' => 'kad-btn',
				'target' => '',
			),
		),
		'naar_scherm_3' =>
		array(
			'ID' => 'naar_scherm_3',
			'type' => 'button',
			'label' => __( 'Volgende', 'siw' ),
			'slug' => 'naar_scherm_3',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'type' => 'next',
				'class' => 'kad-btn kad-btn-primary',
				'target' => '',
			),
		),
		'taal_1' => siw_get_form_field('dropdown',
			array(
				'ID' => 'taal_1',
				'label' => __( 'Taal 1', 'siw' ),
				'slug' => 'taal_1',
				'config' =>
				array(
					'option' => $languages,
				),
			)
		),
		'taal_1_niveau' => siw_get_form_field('radio',
			array(
				'ID' => 'taal_1_niveau',
				'label' => __( 'Niveau taal 1', 'siw' ),
				'slug' => 'taal_1_niveau',
				'config' =>
				array(
					'inline' => 1,
					'option' => $language_skill_levels,
				),
			)
		),
		'taal_2' => siw_get_form_field('dropdown',
			array(
				'ID' => 'taal_2',
				'label' => __( 'Taal 2', 'siw' ),
				'slug' => 'taal_2',
				'required' => 0,
				'config' =>
				array(
					'option' => $languages,
				),
			)
		),
		'taal_2_niveau' => siw_get_form_field('radio',
			array(
				'ID' => 'taal_2_niveau',
				'label' => __( 'Niveau taal 2', 'siw' ),
				'slug' => 'taal_2_niveau',
				'required' => 0,
				'config' =>
				array(
					'inline' => 1,
					'option' => $language_skill_levels,
				),
			)
		),
		'taal_3' => siw_get_form_field('dropdown',
			array(
				'ID' => 'taal_3',
				'label' => __( 'Taal 3', 'siw' ),
				'slug' => 'taal_3',
				'required' => 0,
				'config' =>
				array(
					'option' => $languages,
				),
			)
		),
		'taal_3_niveau' => siw_get_form_field('radio',
			array(
				'ID' => 'taal_3_niveau',
				'label' => __( 'Niveau taal 3', 'siw' ),
				'slug' => 'taal_3_niveau',
				'required' => 0,
				'config' =>
				array(
					'inline' => 1,
					'option' => $language_skill_levels,
				),
			)
		),
		'terug_naar_scherm_2' =>
		array(
			'ID' => 'terug_naar_scherm_2',
			'type' => 'button',
			'label' => __( 'Vorige', 'siw'),
			'slug' => 'terug_naar_scherm_2',
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'type' => 'prev',
				'class' => 'kad-btn',
				'target' => '',
			),
		),
		'verzenden' => siw_get_standard_form_field( 'verzenden' ),
	),
	'auto_progress' => 1,
	'page_names' =>
	array(
		0 => __( 'Personalia', 'siw' ),
		1 => __( 'Project', 'siw' ),
		2 => __( 'Talenkennis', 'siw' ),
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
				'sender_email' => siw_get_setting( 'op_maat_email_sender' ),
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
		'sender_email' => siw_get_setting( 'op_maat_email_sender' ),
		'reply_to' => '%email%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'op_maat_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
	),
);
} );
