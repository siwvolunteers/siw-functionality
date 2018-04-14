<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'siw_settings_show_np_section', function() {
	/* Velden */
	$form_fields = array(
		array(
			'id'			=> 'np_camp_leader_email_section_start',
			'title'			=> __( 'E-mail projectbegeleider', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'np_camp_leader_email_sender',
			'title'			=> __( 'Afzender', 'siw' ),
			'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			'placeholder'	=> 'info@siw.nl',
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'np_camp_leader_email_signature',
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
			'id'			=> 'np_camp_leader_email_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
		array(
			'id'			=> 'np_cooperation_email_section_start',
			'title'			=> __( 'E-mail samenwerking', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'np_cooperation_email_sender',
			'title'			=> __( 'Afzender', 'siw' ),
			'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
			'placeholder'	=> 'info@siw.nl',
			'type'			=> 'text',
			'validate'		=> 'email',
		),
		array(
			'id'			=> 'np_cooperation_email_signature',
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
			'id'			=> 'np_cooperation_email_section_end',
			'type'			=> 'section',
			'indent'		=> false,
		),
	);

	$provinces = siw_get_dutch_provinces();
	$work_types = siw_get_dutch_project_work_types();

//	$languages = apply_filters( 'wpml_active_languages', array() );
//$my_default_lang = apply_filters('wpml_default_language', NULL );
	//siw_debug( $my_default_lang);

	for ( $x = 1 ; $x <= SIW_MAX_DUTCH_PROJECTS; $x++ ) {

		$required = array(
			"np_project_{$x}_present",
			'equals',
			1,
		);

		$map_fields[] = array(
			'id'		=> "np_project_{$x}_section_start",
			'type'		=> 'section',
			'title'		=> __( "Project {$x}", 'siw' ),
			'indent' 	=> true,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_present",
			'title'		=> __( 'Aanwezig', 'siw' ),
			'type'		=> 'switch',
			'on'		=> 'Aan',
			'off'		=> 'Uit',
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_name",
			'title'		=> __( 'Naam', 'siw' ), //TODO: meertalige naam
			'type'		=> 'text',
			'validate'	=> 'no_html',
			'required'	=> $required,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_city",
			'title'		=> __( 'Plaats', 'siw' ),
			'type'		=> 'text',
			'validate'	=> 'no_html',
			'required'	=> $required,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_province",
			'title'		=> __( 'Provincie', 'siw' ),
			'type'		=> 'select',
			'options'	=> $provinces,
			'required'	=> $required,
		);
		$map_fields[] =array(
			'id'		=> "np_project_{$x}_latitude",
			'title'		=> __( 'Breedtegraad', 'siw' ),
			'type'		=> 'text',
			'validate_callback'	=> 'siw_settings_validate_latitude',
			'required'	=> $required,
		);
		$map_fields[] =array(
			'id'		=> "np_project_{$x}_longitude",
			'title'		=> __( 'Lengtegraad', 'siw' ),
			'type'		=> 'text',
			'validate_callback'	=> 'siw_settings_validate_longitude',
			'required'	=> $required,
		);

		$map_fields[] = array(
			'id'		=> "np_project_{$x}_start_date",
			'title'		=> __( 'Startdatum', 'siw' ),
			'type'		=> 'html5',
			'html5'		=> 'date',
			'required'	=> $required,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_end_date",
			'title'		=> __( 'Einddatum', 'siw' ),
			'type'		=> 'html5',
			'html5'		=> 'date',
			'required'	=> $required,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_work",
			'title'		=> __( 'Soort werk', 'siw' ),
			'type'		=> 'select',
			'options'	=> $work_types,
			'required'	=> $required,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_participants",
			'title'		=> __( 'Aantal deelnemers', 'siw' ),
			'type'		=> 'slider',
			'min'		=> '1',
			'max'		=> '50',
			'default'	=> '1',
			'required'	=> $required,
		);
		$map_fields[] = array(
			'id'		=> "np_project_{$x}_section_end",
			'type'		=> 'section',
			'indent'	=> false,
		);
	}

	/* Sectie */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'np',
		'title'			=> __( 'Nederlandse projecten', 'siw' ),
		'icon'			=> 'el el-flag',
		'permissions'	=> 'manage_options',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'np_camp_leader',
		'title'			=> __( 'Formulieren', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $form_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'np_map',
		'title'			=> __( 'Kaart', 'siw' ),
		'heading'		=> __( 'Projectoverzicht', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $map_fields,
	) );
} );
