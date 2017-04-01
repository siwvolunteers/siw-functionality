<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_evs_section', function() {
	/* Velden */
	$deadline_fields[] = array(
		'id'			=> 'evs_min_weeks_before_deadline',
		'title'			=> __( 'Minimum aantal weken voor deadline', 'siw' ),
		'type'			=> 'slider',
		'min'			=> '1',
		'max'			=> '10',
		'default'		=> '4',
	);
	$deadline_fields[] = array(
		'id'			=> 'evs_deadlines_section_start',
		'title'			=> __( 'Deadlines', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	for ($x = 1 ; $x <= SIW_NUMBER_OF_EVS_DEADLINES; $x++) {
		$deadline_fields[] = array(
			'id'			=> "evs_deadline_{$x}",
			'title'			=> __( "Deadline {$x}", 'siw' ),
			'type'			=> 'html5',
			'html5'			=> 'date',
		);
	}
	$deadline_fields[] = array(
		'id'			=> 'evs_deadlines_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);
	$application_fields[] = array(
		'id'			=> 'evs_application_signature_section_start',
		'title'			=> __( 'Ondertekening e-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$application_fields[] = array(
		'id'			=> 'evs_application_signature_name',
		'title'			=> __( 'Naam', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$application_fields[] = array(
		'id'			=> 'evs_application_signature_title',
		'title'			=> __( 'Functie', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'no_special_chars',
	);
	$application_fields[] = array(
		'id'			=> 'evs_application_signature_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);
	/* Sectie */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'evs',
		'title'			=> __( 'EVS', 'siw' ),
		'icon'			=> 'el el-list-alt',
		'permissions'	=> 'manage_options',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'evs_application',
		'title'			=> __( 'Aanmeldformulier', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $application_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'evs_deadlines',
		'title'			=> __( 'Deadlines', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $deadline_fields,
	) );
} );
