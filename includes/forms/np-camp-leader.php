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
		'remove_linebreaks' => true,
	);
	/*E-mail notificatie*/
	$notification_template_args = array(
		'subject' => 'Aanmelding projectbegeleider',
		'message' => 'Via de website is onderstaande aanmelding voor begeleider NP binnengekomen:',
		'show_summary' => true,
	);

return array(
	'ID'			=> 'begeleider_np',
	'name'			=> __('Projectbegeleider NP', 'siw'),
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
	'success'			=> __( 'Je bericht werd succesvol verzonden.', 'siw'),//TODO
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
			'submit' => '9:1',
		),
		'structure' => '12|6:6|6:6|6:6|6:6|6:6|6:6|6:6|12',
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
				'default' => 'De Nederlandse vrijwilligersprojecten vinden plaats in de zomermaanden. We zijn hiervoor altijd op zoek naar projectbegeleiders. Meld je aan via onderstaand formulier en geef aan naar welk project je voorkeur uitgaat. Vervolgens ontvang je een uitnodiging voor een kennismakingsgesprek. In dit gesprek hopen wij erachter te komen wat je verwachtingen en kwaliteiten zijn.',
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
		'achternaam' =>
		array(
			'ID' => 'achternaam',
			'type' => 'text',
			'label' => __( 'Achternaam', 'siw' ),
			'slug' => 'achternaam',
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
		'geboortedatum' =>
		array(
			'ID' => 'geboortedatum',
			'type' => 'text',
			'label' => __( 'Geboortedatum', 'siw' ),
			'slug' => 'geboortedatum',
			'required' => '1',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'placeholder' => 'dd-mm-jjjj',
				'default' => '',
				'masked' => 0,
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'geslacht' =>
		array(
			'ID' => 'geslacht',
			'type' => 'radio',
			'label' => __( 'Geslacht', 'siw' ),
			'slug' => 'geslacht',
			'required' => 1,
			'conditions' =>
			array(
				'type' => '',
			),
			'caption' => '',
			'config' =>
			array(
				'custom_class' => '',
				'inline' => 1,
				'default' => '',
				'option' =>
				array(
					'man' =>
					array(
						'value' => 'man',
						'label' => __( 'Man', 'siw' ),
					),
					'vrouw' =>
					array(
						'value' => 'vrouw',
						'label' => __( 'Vrouw', 'siw' ),
					),
				),
			),
		),
		'emailadres' =>
		array(
			'ID' => 'emailadres',
			'type' => 'email',
			'label' => __( 'E-mailadres', 'siw' ),
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
			'label' => __( 'Telefoonnummer', 'siw' ),
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
		'postcode' =>
		array(
			'ID' => 'postcode',
			'type' => 'text',
			'label' => __( 'Postcode', 'siw' ),
			'slug' => 'postcode',
			'required' => '1',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => 'postcode',
				'placeholder' => '1234 AB',
				'default' => '',
				'masked' => 0,
				//'mask' => '9999 aa',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'huisnummer' =>
		array(
			'ID' => 'huisnummer',
			'type' => 'text',
			'label' => __( 'Huisnummer', 'siw' ),
			'slug' => 'huisnummer',
			'required' => '1',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => 'huisnummer',
				'placeholder' => '',
				'default' => '',
				'masked' => 0,
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'straat' =>
		array(
			'ID' => 'straat',
			'type' => 'text',
			'label' => __( 'Straat', 'siw' ),
			'slug' => 'straat',
			'required' => '1',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => 'straat',
				'placeholder' => '',
				'default' => '',
				'masked' => 0,
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'woonplaats' =>
		array(
			'ID' => 'woonplaats',
			'type' => 'text',
			'label' => __( 'Woonplaats', 'siw' ),
			'slug' => 'woonplaats',
			'required' => '1',
			'caption' => '',
			'config' =>
			array(
				'custom_class' => 'plaats',
				'placeholder' => '',
				'default' => '',
				'masked' => 0,
				'mask' => '',
			),
			'conditions' =>
			array(
				'type' => '',
			),
		),
		'motivatie' =>
		array(
			'ID' => 'motivatie',
			'type' => 'paragraph',
			'label' => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
			'slug' => 'motivatie',
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
				'option' => //TODO: automatisch genereren o.b.v. groepsprojecten
				array(
					'haarzuilens' =>
					array(
						'value' => 'haarzuilens',
						'label' => __( 'Emmaus Haarzuilens', 'siw' ),
					),
					'emma' =>
					array(
						'value' => 'emma',
						'label' => __( 'EMMA Centrum', 'siw' ),
					),
					'knhs' =>
					array(
						'value' => 'knhs',
						'label' => __( 'KNHS Ermelo', 'siw' ),
					),
					'oudemolen' =>
					array(
						'value' => 'oudemolen',
						'label' => __( 'Staatsbosbeheer Oudemolen', 'siw' ),
					),
					'azc' =>
					array(
						'value' => 'azc',
						'label' => __( 'Gezinslocatie AZC Emmen', 'siw' ),
					),
					'twijzel' =>
					array(
						'value' => 'twijzel',
						'label' => __( 'Staatsbosbeheer Twijzel', 'siw' ),
					),
				),
			),
	    ),
		'bekend' =>
		array(
			'ID' => 'bekend',
			'type' => 'checkbox',
			'label' => __( 'Hoe heb je van SIW gehoord?', 'siw' ),
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
		'opmerkingen' =>
		array(
			'ID' => 'opmerkingen',
			'type' => 'paragraph',
			'label' => __( 'Overige opmerkingen?', 'siw' ),
			'slug' => 'opmerkingen',
			'required' => 0,
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
		'sender_email' => siw_get_setting( 'np_camp_leader_email_sender' ),
		'reply_to' => '%email%',
		'email_type' => 'html',
		'recipients' => siw_get_setting( 'np_camp_leader_email_sender' ),
		'email_subject' => $notification_template_args['subject'],
		'email_message' => siw_get_email_template( $notification_template_args ),
	),
);
} );
