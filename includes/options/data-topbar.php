<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor Topbar
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_settings_pages', function( $pages ) {
	$pages[] = [
		'id'         => 'siw-options-topbar',
		'capability' => 'edit_posts',
		'menu_title' => __( 'Topbar', 'siw' ),
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $meta_boxes ) {

	$networks = siw_get_social_networks( 'follow' );
	foreach ( $networks as $network ) {
		$social_networks[ $network->get_slug() ] = $network->get_name();
	}

	$meta_boxes[] = [
		'id'             => 'topbar',
		'title'          => __( 'Topbar', 'siw' ),
		'settings_pages' => 'siw-options-topbar',
		'fields' => [
			[
				'type'              => 'heading',
				'name'              => __( 'Social Media', 'siw' ),
			],
			[
				'id'                => 'topbar_social_link_enabled',
				'name'              => __( 'Link naar social media', 'siw' ),
				'type'              => 'switch',
				'on_label'          => __( 'Aan', 'siw' ),
				'off_label'         => __( 'Uit', 'siw'),
			],
			[
				'id'                => 'topbar_social_link_intro',
				'name'              => __( 'Introtekst', 'siw' ),
				'type'              => 'text',
				'label_description' => __( 'Verborgen op mobiel', 'siw' ),
				'required'          => true,
				'visible'           => [ 'topbar_social_link_enabled', true ],
			],
			[
				'id'                => 'topbar_social_link_text',
				'name'              => __( 'Linktekst', 'siw' ),
				'type'              => 'text',
				'required'          => true,
				'visible'           => [ 'topbar_social_link_enabled', true ],
			],
			[
				'id'                => 'topbar_social_link_network',
				'name'              => __( 'Netwerk', 'siw' ),
				'type'              => 'button_group',
				'options'           => $social_networks,
				'required'          => true,
				'visible'           => [ 'topbar_social_link_enabled', true ],
			],
			[
				'id'                => 'topbar_social_link_start_date',
				'name'              => __( 'Startdatum', 'siw' ),
				'type'              => 'date',
				'required'          => true,
				'visible'           => [ 'topbar_social_link_enabled', true ],
			],
			[
				'id'                => 'topbar_social_link_end_date',
				'name'              => __( 'Einddatum', 'siw' ),
				'type'              => 'date',
				'required'          => true,
				'visible'           => [ 'topbar_social_link_enabled', true ],
			],

		],
	];
	return $meta_boxes;
});