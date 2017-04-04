<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_op_maat_section', function() {
	/* Velden */
	$application_fields[] = array(
		'id'			=> 'op_maat_application_signature_section_start',
		'title'			=> __( 'Ondertekening e-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$application_fields[] = array(
		'id'			=> 'op_maat_application_signature_name',
		'title'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_html',
	);
	$application_fields[] = array(
		'id'			=> 'op_maat_application_signature_title',
		'title'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_html',
	);
	$application_fields[] = array(
		'id'			=> 'op_maat_application_signature_section_end',
		'type'			=> 'section',
		'indent'		=> false,
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
