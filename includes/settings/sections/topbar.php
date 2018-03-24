<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'siw_settings_show_topbar_section', function() {

	$topbar_fields = array(
/* 		array(
			'id'      => 'topbar_order',
			'type'    => 'sorter',
			'title'   => __( 'Prioriteit', 'siw' ),
			'desc'    => __( 'Kies de volgorde waarin', 'siw' ),
			'options' => array(
				'Topbar'  => array(
					'event'			=> __( 'Evenementen', 'siw' ),
					'job'			=> __( 'Vacature', 'siw' ),
					'social_link'	=> __( 'Social media', 'siw' ),
				),
			),
		), */
		array(
			'id'			=> 'topbar_event_section_start',
			'title'			=> __( 'Evenement in topbar', 'siw' ),
			'type'			=> 'section',
			'indent' 		=> true,
		),
		array(
			'id'		=> 'topbar_event_days_range',
			'title'		=> __( 'Datum range', 'siw' ),
			'desc'    	=> __( 'Toon evenement als het meer dan x en minder dan y dagen in de toekomst ligt', 'siw' ),//TODO:goede uitleg
			'type'		=> 'slider',
			'handles'	=> 2,
			'min'		=> 1,
			'max'		=> 31,
			'default'	=> array(
				1	=> 3,
				2	=> 15,
			),
		),
		array(
			'id'			=> 'topbar_event_section_end',
			'type'			=> 'section',
			'indent' 		=> false,
		),
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