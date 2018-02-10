<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_workcamps_section', function() {

	/*
	 * E-mail aanmeldingsformulier
 	 * - Ondertekening
 	 * - TODO: bijlages
	 */

	$application_fields = array(
		array(
			'id'			=> 'workcamp_application_signature_section_start',
			'title'			=> __( 'Ondertekening e-mail', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'workcamp_application_signature_name',
			'title'			=> __( 'Naam', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
		),
		array(
			'id'			=> 'workcamp_application_signature_title',
			'title'			=> __( 'Functie', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
		),
		array(
			'id'			=> 'workcamp_application_signature_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);

	/*
	 * Plato
 	 * - Import
 	 * - Export
	 * - Update
	 */
	$plato_fields = array(
		array(
			'id'			=> 'plato_export_section_start',
			'title'			=> __( 'Export', 'siw' ),
			'subtitle'		=> __( 'Afzender van de aanmelding bij export naar Plato', 'siw' ),
			'type'			=> 'section',
			'indent'		=> true,
		),
		array(
			'id'			=> 'plato_export_outgoing_placements_name',
			'title'			=> __( 'Naam', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
		),
		array(
			'id'			=> 'plato_export_outgoing_placements_email',
			'title'			=> __( 'E-mail', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'plato_export_placements_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
		array(
			'id'			=> 'plato_update_section_start',
			'title'			=> __( 'Update', 'siw' ),
			'type'			=> 'section',
			'indent'		=> true,
		),
		array(
			'id'			=> 'plato_hide_project_days_before_start',
			'title'			=> __( 'Verberg project vanaf aantal dagen voor start project', 'siw' ),
			'type'			=> 'slider',
			'min'			=> '1',
			'max'			=> '28',
			'default'		=>	'7',
		),
		array(
			'id'			=> 'plato_update_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
		array(
			'id'			=> 'plato_import_section_start',
			'title'			=> __( 'Import', 'siw' ),
			'type'			=> 'section',
			'indent'		=> true,
		),
		array(
			'id'			=> 'plato_import_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Import', 'siw' ),
			'indent'		=> true,
		),
		array(
			'id'			=> 'plato_import_supervisor',
			'title'			=> __( 'Coördinator voor Plato-import', 'siw' ),
			'desc'			=> __( 'Staat op cc van mails naar regiospecialisten en ontvangt niet-toegewezen projecten.', 'siw' ),
			'type'			=> 'select',
			'data'			=> 'users',
			'placeholder'	=> __( 'Selecteer een coördinator', 'siw' ),
		),
		array(
			'id'			=> 'plato_import_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);
	/* Secties */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'workcamps',
		'title'			=> __( 'Groepsprojecten', 'siw' ),
		'icon'			=> 'el el-group',
		'permissions'	=> 'manage_options',
	));
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'workcamps_application',
		'title'			=> __( 'Aanmeldformulier', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $application_fields,
	));
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'workcamps_plato_import',
		'title'			=> __( 'Plato', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $plato_fields,
	));
});
