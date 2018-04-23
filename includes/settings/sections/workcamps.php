<?php
/*
 * (c)2016-2018 SIW Internationale Vrijwilligersprojecten
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
			'id'			=> 'workcamp_application_email_section_start',
			'title'			=> __( 'E-mail', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'workcamp_application_email_sender',
			'title'			=> __( 'Afzender', 'siw' ),
			'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			'placeholder'	=> 'info@siw.nl',
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'workcamp_application_email_signature',
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
			'id'			=> 'workcamp_application_email_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);
	$archive_fields = array(
		array(
			'id'			=> 'workcamp_teaser_section_start',
			'type'			=> 'section',
			'title'			=> __( 'Aankondiging nieuwe Groepsprojecten', 'siw' ),
			'indent' 		=> true,
		),
		array(
			'id'			=> 'workcamp_teaser_text_enabled',
			'title'			=> __( 'Aankondiging tonen', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'workcamp_teaser_text_end_date',
			'title'			=> __( 'Einddatum aankonding', 'siw' ),
			'type'			=> 'html5',
			'html5'			=> 'date',
			'required'		=> array(
				'workcamp_teaser_text_enabled',
				'equals',
				1
			),
		),		
		array(
			'id'			=> 'workcamp_teaser_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
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
		'title'			=> __( 'Aanmelding', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $application_fields,
	));
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'workcamps_archive',
		'title'			=> __( 'Overzichtspagina', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $archive_fields,
	));
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'workcamps_plato_import',
		'title'			=> __( 'Plato', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $plato_fields,
	));
});
