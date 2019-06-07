<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor datums
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

add_filter( 'siw_settings_pages', function( $pages ) {
	$pages[] = [
		'id'          => 'siw-options-dates',
		'menu_title'  => __( 'Datums', 'siw' ),
		'capability'  => 'edit_posts',
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $meta_boxes ) {

	$meta_boxes[] = [
		'id'             => 'dates',
		'title'          => __( 'Datums', 'siw' ),
		'settings_pages' => 'siw-options-dates',
		'tabs'           => [
			'esc'            => __( 'ESC', 'siw' ),
			'info_day'       => __( 'Infodag', 'siw' ),
		],
		'tab_style'      => 'left',
		'fields' => [
			[
				'type'       => 'heading',
				'name'       => __( 'Infodagen', 'siw' ),
				'tab'        => 'info_day',
			],
			[
				'id'         => 'info_days',
				'name'       => __( 'Datums', 'siw' ),
				'type'       => 'date',
				'tab'        => 'info_day',
				'js_options' => [
					'dateFormat'      => 'yy-mm-dd',
				],
				'clone'      => true,
				'add_button' => __( 'Toevoegen', 'siw' ),
			],
			[
				'type'       => 'heading',
				'name'       => __( 'ESC deadlines', 'siw' ),
				'tab'        => 'esc',
			],
			[
				'id'         => 'esc_deadlines',
				'name'       => __( 'Datums', 'siw' ),
				'type'       => 'date',
				'tab'        => 'esc',
				'js_options' => [
					'dateFormat'      => 'yy-mm-dd',
				],
				'clone'      => true,
				'add_button' => __( 'Toevoegen', 'siw' ),
			],
		],
	];
	return $meta_boxes;
});