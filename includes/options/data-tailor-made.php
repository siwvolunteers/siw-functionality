<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */


add_filter( 'siw_settings_pages', function( $pages ) {

	$pages[] = [
		'id'            => 'siw-options-tailor-made',
		'menu_title'    => __( 'Op Maat', 'siw' ),
		'capability'    => 'manage_options',
	];

	return $pages;
} );

add_filter( 'siw_settings_meta_boxes', function( $boxes ) {

	$boxes[] = [
		'id'             => 'email',
		'title'          => __( 'Op Maat', 'siw' ),
		'settings_pages' => 'siw-options-tailor-made',
		'fields' => [
			[
				'id'      => 'tailor_made_sale',
				'type'    => 'group',
				'tab'     => 'tailor_made',
				'fields'  => [
					[
						'id'                => 'active',
						'name'              => __( 'Kortingsactie actief', 'siw' ),
						'type'              => 'switch',
						'on_label'          => __( 'Ja', 'siw' ),
						'off_label'         => __( 'Nee', 'siw'),
					],
					[
						'id'                => 'start_date',
						'name'              => __( 'Startdatum kortingsactie', 'siw' ),
						'type'              => 'date',
						'visible'           => [ 'active', true ],
					],
					[
						'id'                => 'end_date',
						'name'              => __( 'Einddatum kortingsactie', 'siw' ),
						'type'              => 'date',
						'visible'           => [ 'active', true ],
					],
					[
						'type'              => 'custom_html',
						'visible'           => [ 'active', true ],
						'std'               => sprintf( 'Regulier: %s, Student: %s', SIW_Properties::TAILOR_MADE_FEE_REGULAR_SALE, SIW_Properties::TAILOR_MADE_FEE_STUDENT_SALE ),
						//TODO: netter + i18n
					],
				],
			],
		],
	];
	return $boxes;

});