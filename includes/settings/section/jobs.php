<?php
/*
(c)2016-2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('siw_settings_show_jobs_section', 'siw_settings_show_jobs_section');
function siw_settings_show_jobs_section( $opt_name ){
	/*
	Velden
	*/
	$jobs_fields[] = array(
		'id'		=> 'company_profile',
		'title'		=> __( 'Wie zijn wij', 'siw' ),
		'type'		=> 'editor',
		'args'		=> array(
			'teeny'			=> true,
			'media_buttons'	=> false,
			'wpautop'		=> false,
		),
		'validate' => 'html',
	);
	$jobs_fields[] = array(
		'id'		=> 'mission_statement',
		'title'		=> __( 'Missie', 'siw' ),
		'type'		=> 'editor',
		'args'		=> array(
			'teeny'			=> true,
			'media_buttons'	=> false,
			'wpautop'		=> false,
		),
		'validate' => 'html',
	);

	/*
	Sectie
	*/
	Redux::setSection( $opt_name, array(
		'id'			=> 'jobs',
		'title'			=> __( 'Vacatures', 'siw' ),
		'desc'			=> __( 'Standaardteksten voor vacatures', 'siw' ),
		'icon'			=> 'el el-paper-clip',
		'fields'		=> $jobs_fields,
	));
}
