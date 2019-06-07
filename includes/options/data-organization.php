<?php
/**
 * Opties voor organisatie-gegevens
 * 
 * @package   SIW\Options
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

add_filter( 'siw_settings_pages', function( $pages ) {
	$pages[] = [
		'id'         => 'siw-options-organization',
		'capability' => 'edit_posts',
		'menu_title' => __( 'Organisatie', 'siw' ),
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $meta_boxes ) {
	$meta_boxes[] = [
		'id'             => 'board_members',
		'title'          => __( 'Bestuur', 'siw' ),
		'settings_pages' => 'siw-options-organization',
		'fields'         => [
			[
				'id'            => 'board_members',
				'name'          => 'Bestuursleden',
				'type'          => 'group',
				'clone'         => true,
				'sort_clone'    => true,
				'max_clone'     => SIW_Properties::MAX_BOARD_MEMBERS,
				'collapsible'   => true,
				'default_state' => 'collapsed',
				'group_title'   => [ 'field' => 'first_name, last_name'],
				'add_button'    => __( 'Toevoegen', 'siw' ),
				'fields' => [
					[
						'id'       => 'first_name',
						'name'     => __( 'Voornaam', 'siw' ),
						'type'     => 'text',
						'required' => true,
					],
					[
						'id'       => 'last_name',
						'name'     => __( 'Achternaam', 'siw' ),
						'type'     => 'text',
						'required' => true,
					],
					[
						'id'      => 'title',
						'name'    => __( 'Functie', 'siw' ),
						'type'    => 'button_group',
						'options' => siw_get_board_titles(),
					],
				]
			]
		],
	];
	$meta_boxes[] = [
		'id'             => 'annual_reports',
		'title'          => __( 'Jaarverslagen', 'siw' ),
		'settings_pages' => 'siw-options-organization',
		'context'        => 'side',
		'fields' => [
			[
				'id'            => 'annual_reports',
				'name'          => __( 'Jaarverslagen', 'siw' ),
				'type'          => 'group',
				'clone'         => true,
				'sort_clone'    => true,
				'max_clone'     => SIW_Properties::MAX_ANNUAL_REPORTS,
				'collapsible'   => true,
				'default_state' => 'collapsed',
				'group_title'   => [ 'field' => 'year'],
				'add_button'    => __( 'Toevoegen', 'siw' ),
				'fields'        => [
					[
						'id'       => 'year',
						'name'     => __( 'Jaar', 'siw' ),
						'type'     => 'number',
						'required' => true,
						'min'      => 2012, //TODO: property, bijvoorbeeld oprichtingsjaar
						'max'      => intval(date( 'Y' ) )
					],
					[
						'id'               => 'file',
						'name'             => __( 'Bestand', 'siw' ),
						'type'             => 'file_advanced',
						'required'         => true,
						'max_file_uploads' => 1,
						'mime_type'        => 'application/pdf',
						'force_delete'     => true,
					],
				]
			]
		],
	];
	return $meta_boxes;
});