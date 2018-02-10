<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_op_maat_section', function() {

	$application_fields = array(
		array(
			'id'			=> 'op_maat_email_section_start',
			'title'			=> __( 'E-mail', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'op_maat_email_sender',
			'title'			=> __( 'Afzender', 'siw' ),
			'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'op_maat_email_signature',
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
			'id'			=> 'op_maat_email_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);
	/* Sectie */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'op_maat',
		'title'			=> __( 'Op Maat', 'siw' ),
		'icon'			=> 'el el-user',
		'permissions'	=> 'manage_options',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'op_maat_application',
		'title'			=> __( 'Aanmeldformulier', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $application_fields,
	) );
} );
