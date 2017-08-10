<?php
/*
 * (c)2016-2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* edit_jobs toevoegen aan caps op optiemenu te zien */
add_filter( 'siw_manage_settings_caps', function( $caps ) {
	$caps[] = 'edit_jobs';
	return $caps;
});

add_action( 'siw_settings_show_jobs_section', function() {
	/* Velden */
	$jobs_fields[] = array(
		'id'		=> 'company_profile',
		'title'		=> __( 'Wie zijn wij', 'siw' ),
		'subtitle'	=> __( 'Wordt gebruikt in vacaturebeschrijving', 'siw'),
		'type'		=> 'editor',
		'args'		=> array(
			'teeny'			=> true,
			'media_buttons'	=> false,
			'wpautop'		=> false,
			'textarea_rows'	=> 10,
		),
		'validate' => 'html',
	);
	/* Sectie */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'jobs',
		'title'			=> __( 'Vacatures', 'siw' ),
		'icon'			=> 'el el-paper-clip',
		'permissions'	=> 'edit_jobs',
		'fields'		=> $jobs_fields,
	) );
} );
