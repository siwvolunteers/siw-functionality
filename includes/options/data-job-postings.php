<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. configuratie
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_settings_pages', function( $pages ) {
	$pages[] = [
		'id'          => 'siw-options-job-postings',
		'menu_title'  => __( 'Vacatures', 'siw' ),
		'capability'  => 'manage_options',
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $meta_boxes ) {
	$meta_boxes[] = [
		'id'             => 'job_postings',
		'title'          => __( 'Vacatures', 'siw' ),
		'settings_pages' => 'siw-options-job-postings',

		'fields'         => [
			[
				'type'     => 'heading',
				'name'     => __( 'Vacaturetekst', 'siw' ),
			],
			[
				'id'       => 'job_postings_organization_profile',
				'name'     => __( 'Wie zijn wij', 'siw' ),
				'type'     => 'wysiwyg',
				'raw'      => true,
				'options'  => [
					'teeny'         => true,
					'media_buttons' => false,
					'teeny'         => true,
					'textarea_rows' => 5,
				],
			],
		],
	];

	return $meta_boxes;
});
