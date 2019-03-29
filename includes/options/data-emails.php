<?php
/**
 * Opties voor landen
 * 
 * @package   SIW\Options
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

/**
 * Opties voor ESC
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_settings_pages', function( $pages ) {
	$pages[] = [
		'id'         => 'siw-options-emails',
		'capability' => 'manage_options',
		'menu_title' => __( 'E-mails', 'siw' ),
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $boxes ) {

	$boxes[] = [
		'id'             => 'emails',
		'title'          => __( 'E-mails', 'siw' ),
		'settings_pages' => 'siw-options-emails',
		'tabs'           => [
			'esc'            => __( 'ESC', 'siw' ),
			'workcamps'      => __( 'Groepsprojecten', 'siw' ),
			'info_day'       => __( 'Infodag', 'siw' ),
			'enquiry'        => __( 'Infoverzoeken', 'siw' ),
			'dutch_projects' => __( 'Nederlandse projecten', 'siw' ),
			'tailor_made'    => __( 'Op Maat', 'siw' ),
			'cooperation'    => __( 'Samenwerking', 'siw' ),
		],
		'tab_style'      => 'left',
		'fields' => [
			[
				'type'              => 'heading',
				'name'              => __( 'Aanmelding ESC', 'siw' ),
				'tab'               => 'esc',
			],
			[
				'id'                => 'esc_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'esc',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'esc_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'esc',
				'options'           => [
					'name'    => __( 'Naam', 'siw' ),
					'title'   => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Aanmelding Op Maat', 'siw' ),
				'tab'               => 'tailor_made',
			],
			[
				'id'                => 'tailor_made_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'tailor_made',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'tailor_made_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'tailor_made',
				'options'           => [
					'name'    => __( 'Naam', 'siw' ),
					'title'   => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Aanmelding Groepsproject', 'siw' ),
				'tab'               => 'workcamps',
			],
			[
				'id'                => 'workcamp_application_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'workcamps',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'workcamp_application_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'workcamps',
				'options'           => [
					'name'  => __( 'Naam', 'siw' ),
					'title' => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __('Infoverzoek algemeen', 'siw' ),
				'tab'               => 'enquiry',
			],
			[
				'id'                => 'enquiry_general_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'enquiry',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'enquiry_general_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'enquiry',
				'options'           => [
					'name'  => __( 'Naam', 'siw' ),
					'title' => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Infoverzoek groepsproject', 'siw' ),
				'tab'               => 'enquiry',
			],
			[
				'id'                => 'enquiry_workcamp_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'enquiry',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'enquiry_workcamp_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'enquiry',
				'options'           => [
					'name'    => __( 'Naam', 'siw' ),
					'title'   => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Aanmelding infodag', 'siw' ),
				'tab'               => 'info_day',
			],
			[
				'id'                => 'info_day_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'info_day',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'info_day_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'info_day',
				'options'           => [
					'name'    => __( 'Naam', 'siw' ),
					'title'   => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Aanmelding projectbegeleider NP', 'siw' ),
				'tab'               => 'dutch_projects',
			],
			[
				'id'                => 'camp_leader_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'dutch_projects',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'camp_leader_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'dutch_projects',
				'options'           => [
					'name'    => __( 'Naam', 'siw' ),
					'title'   => __( 'Functie', 'siw' ),
				],
			],
			[
				'type'              => 'heading',
				'name'              => __( 'Samenwerking', 'siw' ),
				'tab'               => 'cooperation',
			],
			[
				'id'                => 'cooperation_email_sender',
				'name'              => __( 'Afzender', 'siw' ),
				'type'              => 'email',
				'tab'               => 'cooperation',
				'label_description' => __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			],
			[
				'id'                => 'cooperation_email_signature',
				'name'              => __( 'Ondertekening', 'siw' ),
				'type'              => 'fieldset_text',
				'tab'               => 'cooperation',
				'options'           => [
					'name'    => __( 'Naam', 'siw' ),
					'title'   => __( 'Functie', 'siw' ),
				],
			],
		],
	];

	return $boxes;
});
