<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_enquiry_section', function() {
	/* Velden */
	$enquiry_fields = array(
		array(
			'id'			=> 'enquiry_general_email_section_start',
			'title'			=> __( 'E-mail infoverzoek algemeen', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'enquiry_general_email_sender',
			'title'			=> __( 'Afzender', 'siw' ),
			'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'enquiry_general_email_signature',
			'title'			=> __( 'Ondertekening', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'options' 		=> array(
				'name'			=> __( 'Naam', 'siw' ),
				'title'			=> __( 'Functie', 'siw' ),
			),
			'default' => array(
				'name' => '',
				'title' => '',
			),
		),
		array(
			'id'			=> 'enquiry_general_email_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
		array(
			'id'			=> 'enquiry_workcamp_email_section_start',
			'title'			=> __( 'E-mail infoverzoek Groepsproject', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'enquiry_workcamp_email_sender',
			'title'			=> __( 'Afzender', 'siw' ),
			'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'enquiry_workcamp_email_signature',
			'title'			=> __( 'Ondertekening', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'options' 		=> array(
				'name'			=> __( 'Naam', 'siw' ),
				'title'			=> __( 'Functie', 'siw' ),
			),
			'default' => array(
				'name' => '',
				'title' => '',
			),
		),
		array(
			'id'			=> 'enquiry_workcamp_email_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);

	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'enquiry',
		'title'			=> __( 'Infoverzoeken', 'siw' ),
		'icon'			=> 'el el-info-circle',
		'permissions'	=> 'manage_options',
		'fields'		=> $enquiry_fields,
	) );

} );
