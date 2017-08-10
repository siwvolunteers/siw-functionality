<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_enquiry_section', function() {
	/* Velden */
	$general_fields[] = array(
		'id'			=> 'enquiry_general_email_section_start',
		'title'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$general_fields[] = array(
		'id'			=> 'enquiry_general_email_sender',
		'title'			=> __( 'Afzender', 'siw' ),
		'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'email',
	);
	$general_fields[] = array(
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
	);
	$general_fields[] = array(
		'id'			=> 'enquiry_general_email_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);

	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_email_section_start',
		'title'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_email_sender',
		'title'			=> __( 'Afzender', 'siw' ),
		'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'email',
	);
	$workcamp_fields[] = array(
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
	);
	$workcamp_fields[] = array(
		'id'			=> 'enquiry_workcamp_email_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);


	/* Secties */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'enquiry',
		'title'			=> __( 'Infoverzoeken', 'siw' ),
		'icon'			=> 'el el-info-circle',
		'permissions'	=> 'manage_options',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'enquiry_general',
		'title'			=> __( 'Algemeen', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $general_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'enquiry_workcamp',
		'title'			=> __( 'Groepsprojecten', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $workcamp_fields,
	) );
} );
