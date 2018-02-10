<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* edit_pages toevoegen aan caps op optiemenu te zien */
add_filter( 'siw_manage_settings_caps', function( $caps ) {
	$caps[] = 'edit_pages';
	return $caps;
});

add_action( 'siw_settings_show_organisation_section', function() {

	for ( $x = 1 ; $x <= SIW_MAX_BOARD_MEMBERS; $x++ ) {
		$board_fields[] = array(
			'id'		=> "board_member_{$x}",
			'title'		=> __( "Bestuurslid {$x}", 'siw' ),
			'type'		=> 'text',
			'validate'	=> 'no_html',
			'options' 	=> array(
				'name'		=> __( 'Naam', 'siw' ),
				'title'		=> __( 'Functie', 'siw' ),
			),
			'default'	=> array(
				'name'		=> '',
				'title' 	=> '',
			),
			'data' => false,
		);
	}

	$last_year = (int) date( 'Y' ) - 1;
	$first_year = $last_year - SIW_MAX_ANNUAL_REPORTS + 1;

	for ( $x = $last_year ; $x >= $first_year; $x-- ) {
		$annual_report_fields[] = array(
			'id'	=> "annual_report_{$x}",
			'type'	=> 'media',
			'mode'	=> '',
			'library_filter' => array( 'pdf' ),
			'url'	=> true,
			'preview' => false,
			'title'	=> __( "Jaarverslag {$x}", 'siw' ),
		);
	}

	/* Sectie */
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'organisation',
		'title'			=> __( 'Organisatie', 'siw' ),
		'icon'			=> 'el el-flag',
		'permissions'	=> 'edit_pages',
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'organisation_board',
		'title'			=> __( 'Bestuur', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $board_fields,
	) );
	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'np_annual_report',
		'title'			=> __( 'Jaarverslag', 'siw' ),
		'subsection'	=> true,
		'fields'		=> $annual_report_fields,
	) );
} );
