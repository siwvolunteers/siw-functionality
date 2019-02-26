<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_topbar_section', function() {

	$topbar_fields = array(
		array(
			'id'			=> 'topbar_social_link_section_start',
			'title'			=> __( 'Social media in topbar', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'			=> 'topbar_social_link_enabled',
			'title'			=> __( 'Link naar social media', 'siw' ),
			'type'			=> 'switch',
			'on'			=> 'Aan',
			'off'			=> 'Uit',
		),
		array(
			'id'			=> 'topbar_social_link_intro',
			'title'			=> __( 'Introtekst', 'siw' ),
			'subtitle'		=> __( 'Verborgen op mobiel', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_text',
			'title'			=> __( 'Linktekst', 'siw' ),
			'type'			=> 'text',
			'validate'		=> 'no_html',
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_network',
			'title'			=> __( 'Netwerk', 'siw' ),
			'type'			=> 'radio',
			'options'		=> array(
				'facebook'		=> __( 'Facebook', 'siw' ),
				'instagram'		=> __( 'Instagram', 'siw' ),
				'twitter'		=> __( 'Twitter', 'siw' ),
			),
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_date_end',
			'title'			=> __( 'Einddatum', 'siw' ),
			'type'			=> 'html5',
			'html5'			=> 'date',
			'required'		=> array(
				'topbar_social_link_enabled',
				'equals',
				'1'
			),
		),
		array(
			'id'			=> 'topbar_social_link_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),
	);

	Redux::setSection( SIW_OPT_NAME, array(
		'id'			=> 'topbar',
		'title'			=> __( 'Topbar', 'siw' ),
        'icon'			=> 'el el-bullhorn',
		'permissions'	=> 'manage_options',
		'fields'		=> $topbar_fields,
	) );
} );    