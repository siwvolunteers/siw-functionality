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
		'id'         => 'siw-options-dutch-projects',
		'menu_title' => __( 'Nederlandse Projecten', 'siw' ),
		'capability' => 'edit_posts',
	];
	return $pages;
});

add_filter( 'siw_settings_meta_boxes', function( $meta_boxes ) {

	$languages = SIW_i18n::get_active_languages();
	$types = siw_get_work_types( 'dutch_projects' );

	foreach ( $types as $type ) {
		$work_types[ $type->get_slug() ] = $type->get_name();
	}
	$group_fields = [];
	$group_fields[] = [
		'id'      => 'code',
		'name'    => __( 'Code', 'siw' ),
		'type'    => 'text',
	];
	foreach ( $languages as $code => $language ) {
		$group_fields[] = [
			'id'      => "name_{$code}",
			'name'    => sprintf( __( 'Naam (%s)', 'siw' ), $language['translated_name'] ),
			'type'    => 'text',
		];
	}
	$group_fields[] =
	[
		'id'      => 'city',
		'name'    => __( 'Plaats', 'siw' ),
		'type'    => 'text',
	];
	$group_fields[] = [
		'id'      => 'province',
		'name'    => __( 'Provincie', 'siw' ),
		'type'    => 'select',
		'options' => siw_get_dutch_provinces(),
	];
	$group_fields[] = [
		'id'      => 'latitude',
		'name'    => __( 'Breedtegraad', 'siw' ),
		'type'    => 'text',
		'pattern' => SIW_Util::get_pattern('latitude'),
	];
	$group_fields[] = [
		'id'      => 'longitude',
		'name'    => __( 'Lengtegraad', 'siw' ),
		'type'    => 'text',
		'pattern' => SIW_Util::get_pattern('longitude'),
	];
	$group_fields[] = [
		'id'         => 'start_date',
		'name'       => __( 'Startdatum', 'siw' ),
		'type'       => 'date',
		'timestamp'  => true,
		'readonly'   => true,
		'js_options' => [
			'dateFormat'  => 'd MM yy',
		],
	];
	$group_fields[] = [
		'id'         => 'end_date',
		'name'       => __( 'Einddatum', 'siw' ),
		'type'       => 'date',
		'timestamp'  => true,
		'readonly'   => true,
		'js_options' => [
			'dateFormat'  => 'd MM yy',
		],
	];
	$group_fields[] = [
		'id'      => 'work_type',
		'name'    => __( 'Soort werk', 'siw' ),
		'type'    => 'button_group',
		'options' => $work_types,
	];
	$group_fields[] = [
		'id'   => 'participants',
		'name' => __( 'Aantal deelnemers', 'siw' ),
		'type' => 'number',
		'min'  => 1,
		'max'  => 50,
	];
	$group_fields[] = [
		'id'   => 'local_fee',
		'name' => __( 'Lokale bijdrage', 'siw' ),
		'type' => 'number',
		'min'  => 1,
		'max'  => 999,
	];
	foreach ( $languages as $code => $language ) {
		$group_fields[] = [
			'id'      => "description_{$code}",
			'name'    => sprintf( __( 'Beschrijving (%s)', 'siw' ), $language['translated_name'] ),
			'type'     => 'wysiwyg',
			'raw'      => true,
			'options'  => [
				'teeny'         => true,
				'media_buttons' => false,
				'teeny'         => true,
				'textarea_rows' => 5,
			],
		];
	}
	$group_fields[] = [
		'id'               => 'image',
		'name'             => __( 'Afbeelding', 'siw' ),
		'type'             => 'image_advanced',
		'force_delete'     => true,
		'max_file_uploads' => 1,
		'max_status'       => false,
		'image_size'       => 'thumbnail',
	];

	$meta_boxes[] = [
		'id'             => 'dutch-projects',
		'title'          => __( 'Nederlandse projecten', 'siw' ),
		'settings_pages' => 'siw-options-dutch-projects',
		'fields'         => [
			[
				'id'            => 'dutch_projects',
				'name'          => __( 'Project', 'siw' ),
				'type'          => 'group',
				'clone'         => true,
				'sort_clone'    => true,
				'collapsible'   => true,
				'default_state' => 'collapsed',
				'group_title'   => [ 'field' => 'code, name_nl', 'separator' => ' - ' ],
				'add_button'    => __( 'Toevoegen', 'siw' ),
				'fields'        => $group_fields,
			]
		],
	];

	$meta_boxes[] = [
		'id'             => 'dutch-projects-booklet',
		'title'          => __( 'Programmaboekje', 'siw' ),
		'settings_pages' => 'siw-options-dutch-projects',
		'context'        => 'side',
		'fields'         => [
			[
				'id'   => 'dutch_projects_booklet_year',
				'name' => __( 'Jaar', 'siw' ),
				'type' => 'number',
				'min'  => 2012, //TODO: property, bijvoorbeeld oprichtingsjaar
				'max'  => intval(date( 'Y' ) )
			],
			[
				'id'               => 'dutch_projects_booklet',
				'name'             => __( 'Bestand', 'siw' ),
				'type'             => 'file_advanced',
				'max_file_uploads' => 1,
				'mime_type'        => 'application/pdf',
				'force_delete'     => true,
			],
		],
	];

	return $meta_boxes;
});
