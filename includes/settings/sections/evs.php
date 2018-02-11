<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'siw_manage_settings_caps', function( $caps ) {
	$caps[] = 'edit_evs_projects';
	return $caps;
});


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
		'id'			=> 'evs_email_section_start',
		'title'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$application_fields[] = array(
		'id'			=> 'evs_email_sender',
		'title'			=> __( 'Afzender', 'siw' ),
		'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'email',
	);
	$application_fields[] = array(
		'id'			=> 'evs_email_signature',
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
	$application_fields[] = array(
		'id'			=> 'evs_email_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);
	/* Sectie */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'evs',
		'title'			=> __( 'EVS', 'siw' ),
		'icon'			=> 'el el-list-alt',
		'permissions'	=> 'edit_evs_projects',
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
