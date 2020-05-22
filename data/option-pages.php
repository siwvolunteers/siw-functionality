<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Optiepagina's
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'id'         => 'configuration',
		'menu_title' => __( 'Configuratie', 'siw' ),
		'capability' => 'manage_options',
		'tabs'       => [
			'api'    => [
				'label' => __( 'API', 'siw' ),
				'icon'  => 'dashicons-admin-network',
			],
			'black-white-lists' => [
				'label' => __( 'Black/Whitelists', 'siw' ),
				'icon'  => 'dashicons-shield',
			],
			'email' => [
				'label' => __( 'Email', 'siw' ),
				'icon'  => 'dashicons-email',
			],
			'pages' => [
				'label' => __( "Pagina's", 'siw' ),
				'icon'  => 'dashicons-admin-page',
			],
			'other' => [
				'label' => __( 'Overig', 'siw' ),
				'icon'  => 'dashicons-admin-generic',
			],
		],
	],
	[
		'id'         => 'settings',
		'menu_title' => __( 'Instellingen', 'siw' ),
		'capability' => 'edit_posts',
		'tabs'       => [
			'board'  => [
				'label' => __( 'Bestuur', 'siw' ),
				'icon'  => 'dashicons-businessman',
			],
			'email' => [
				'label' => __( 'Email', 'siw' ),
				'icon'  => 'dashicons-email',
			],
			'workcamps' => [
				'label' => __( 'Groepsprojecten', 'siw' ),
				'icon'  => 'dashicons-groups'
			],
			'annual_reports' => [
				'label' => __( 'Jaarverslagen', 'siw' ),
				'icon'  => 'dashicons-media-document',
			],
			'dutch_projects' => [
				'label' => __( 'Nederlandse projecten', 'siw' ),
				'icon'  => 'dashicons-admin-home'
			],
			'notifications' => [
				'label' => __( 'Notificaties', 'siw' ),
				'icon'  => 'dashicons-megaphone',
			],
			'tailor_made' => [
				'label' => __( 'Op Maat', 'siw' ),
				'icon'  => 'dashicons-admin-settings',
			],
			'opening_hours' => [
				'label' => __( 'Openingstijden', 'siw' ),
				'icon'  => 'dashicons-clock',
			],
			'job_postings' => [
				'label' => __( 'Vacatures', 'siw' ),
				'icon'  => 'dashicons-clipboard'
			],
		],
	],
	[
		'id'         => 'countries',
		'menu_title' => __( 'Landen', 'siw' ),
		'capability' => 'manage_options',
		'tabs'       => siw_get_continents( 'array' ),
	],
];
return $data;