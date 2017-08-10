<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'siw_settings_show_info_day_section', function() {
	/* Velden */


	$application_fields[] = array(
		'id'		=> 'hide_application_form_days_before_info_day',
		'title'		=> __( 'Verberg aanmeldformulier vanaf aantal dagen voor infodag', 'siw' ),
		'type'		=> 'slider',
		'min'		=> '1',
		'max'		=> '31',
		'default'	=> '1',
	);
	$application_fields[] = array(
		'id'			=> 'info_day_email_section_start',
		'title'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$application_fields[] = array(
		'id'			=> 'info_day_email_sender',
		'title'			=> __( 'Afzender', 'siw' ),
		'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
		'type'			=> 'text',
		'validate'		=> 'email',
	);
	$application_fields[] = array(
		'id'			=> 'info_day_email_signature',
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
		'id'			=> 'info_day_email_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);
	$info_day_fields[] = array(
		'id'		=> 'info_day_application_signature_section_start',
		'title'		=> __( 'Ondertekening e-mail', 'siw' ),
		'type'		=> 'section',
		'indent' 	=> true,
	);
	$info_day_fields[] = array(
		'id'		=> 'info_day_application_signature_name',
		'title'		=> __( 'Naam', 'siw' ),
		'type'		=> 'text',
		'validate'	=> 'no_html',
	);
	$info_day_fields[] = array(
		'id'		=> 'info_day_application_signature_title',
		'title'		=> __( 'Functie', 'siw' ),
		'type'		=> 'text',
		'validate'	=> 'no_html',
	);
	$info_day_fields[] = array(
		'id'		=> 'info_day_application_signature_section_end',
		'type'		=> 'section',
		'indent'	=> false,
	);
	$info_day_fields[] = array(
		'id'		=> 'info_day_dates_section_start',
		'title'		=> __( 'Datums', 'siw' ),
		'type'		=> 'section',
		'indent'	=> true,
	);
	for ( $x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++) {
		$info_day_fields[] = array(
			'id'		=> "info_day_{$x}",
			'title'		=> __( "Infodag {$x}", 'siw' ),
			'type'		=> 'html5',
			'html5'		=> 'date',
		);
	}
	$info_day_fields[] = array(
		'id'		=> 'info_day_dates_section_end',
		'type'		=> 'section',
		'indent'	=> false,
	);

	/* Secties */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'agenda',
		'title'			=> __( 'Infodag', 'siw' ),
		'icon'			=> 'el el-calendar',
		'permissions'	=> 'manage_options',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'		=> 'info_day_application',
		'title'		=> __( 'Aanmeldformulier', 'siw' ),
		'subsection'=> true,
		'fields'	=> $application_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'		=> 'info_day',
		'title'		=> __( 'Infodagen', 'siw' ),
		'subsection'=> true,
		'fields'	=> $info_day_fields,
	) );

} );
