<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'siw_settings_show_np_section', function() {
	/* Velden */
	$camp_leader_fields[] = array(
		'id'			=> 'np_camp_leader_email_section_start',
		'title'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$camp_leader_fields[] = array(
		'id'			=> 'np_camp_leader_email_sender',
		'title'			=> __( 'Afzender', 'siw' ),
		'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
		'placeholder'	=> 'info@siw.nl',
		'type'			=> 'text',
		'validate'		=> 'email',
	);
	$camp_leader_fields[] = array(
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
	);
	$camp_leader_fields[] = array(
		'id'			=> 'np_camp_leader_email_section_end',
		'type'			=> 'section',
		'indent'		=> false,
	);

	$cooperation_fields[] = array(
		'id'			=> 'np_cooperation_email_section_start',
		'title'			=> __( 'E-mail', 'siw' ),
		'type'			=> 'section',
		'indent' 		=> true,
	);
	$cooperation_fields[] = array(
		'id'			=> 'np_cooperation_email_sender',
		'title'			=> __( 'Afzender', 'siw' ),
		'subtitle'		=> __( 'Ontvangt ook de bevestigingsmail', 'siw' ),
		'placeholder'	=> 'info@siw.nl',
		'type'			=> 'text',
		'validate'		=> 'email',
	);
	$cooperation_fields[] = array(
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
	);
	$cooperation_fields[] = array(
		'id'			=> 'np_cooperation_email_section_end',
		'type'			=> 'section',
		'indent'		=> false,
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
		'title'			=> __( 'Projectbegeleider', 'siw' ),
		'heading'		=> __( 'Aanmeldformulier projectbegeleider', 'siw'),
		'subsection'	=> true,
		'fields'		=> $camp_leader_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'np_cooperation',
		'title'			=> __( 'Samenwerking', 'siw' ),
		'heading'		=> __( 'Formulier samenwerking NP', 'siw'),
		'subsection'	=> true,
		'fields'		=> $cooperation_fields,
	) );
} );
