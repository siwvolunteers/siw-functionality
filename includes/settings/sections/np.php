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
} );
