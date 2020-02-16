<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\i18n;
use SIW\Util;

/**
 * Opties t.b.v. Nederlandse Projecten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$languages = i18n::get_active_languages();
$work_types = siw_get_work_types( 'dutch_projects', 'slug', 'array' );

$group_fields = [];
$group_fields[] = [
	'id'      => 'code',
	'name'    => __( 'Code', 'siw' ),
	'type'    => 'text',
	'required' => true,
];
foreach ( $languages as $code => $language ) {
	$group_fields[] = [
		'id'      => "name_{$code}",
		'name'    => sprintf( __( 'Naam (%s)', 'siw' ), $language['translated_name'] ),
		'type'    => 'text',
		'required' => true,
	];
}
$group_fields[] =
[
	'id'      => 'city',
	'name'    => __( 'Plaats', 'siw' ),
	'type'    => 'text',
	'required' => true,
];
$group_fields[] = [
	'id'      => 'province',
	'name'    => __( 'Provincie', 'siw' ),
	'type'    => 'select',
	'required' => true,
	'options' => siw_get_dutch_provinces(),
];
$group_fields[] = [
	'id'       => 'latitude',
	'name'     => __( 'Breedtegraad', 'siw' ),
	'type'     => 'text',
	'required' => true,
	'pattern'  => Util::get_pattern('latitude'),
];
$group_fields[] = [
	'id'       => 'longitude',
	'name'     => __( 'Lengtegraad', 'siw' ),
	'type'     => 'text',
	'required' => true,
	'pattern'  => Util::get_pattern('longitude'),
];
$group_fields[] = [
	'id'         => 'start_date',
	'name'       => __( 'Startdatum', 'siw' ),
	'type'       => 'date',
	'required' => true,
];
$group_fields[] = [
	'id'         => 'end_date',
	'name'       => __( 'Einddatum', 'siw' ),
	'type'       => 'date',
	'required'   => true,
];
$group_fields[] = [
	'id'       => 'work_type',
	'name'     => __( 'Soort werk', 'siw' ),
	'type'     => 'radio',
	'required' => true,
	'options'  => $work_types,
];
$group_fields[] = [
	'id'       => 'participants',
	'name'     => __( 'Aantal deelnemers', 'siw' ),
	'type'     => 'number',
	'required' => true,
	'min'      => 1,
	'max'      => 50,
];
$group_fields[] = [
	'id'       => 'local_fee',
	'name'     => __( 'Lokale bijdrage', 'siw' ),
	'type'     => 'number',
	'required' => true,
	'prepend'  => '€',
	'min'      => 1,
	'max'      => 999,
];
foreach ( $languages as $code => $language ) {
	$group_fields[] = [
		'id'       => "description_{$code}",
		'name'     => sprintf( __( 'Beschrijving (%s)', 'siw' ), $language['translated_name'] ),
		'type'     => 'wysiwyg',
		'required' => true,
		'raw'      => true,
		'options'  => [
			'teeny'         => true,
			'media_buttons' => false,
			'textarea_rows' => 5,
		],
	];
}
$group_fields[] = [
	'id'               => 'image',
	'name'             => __( 'Afbeelding', 'siw' ),
	'type'             => 'image_advanced',
	'required'         => true,
	'force_delete'     => false,
	'max_file_uploads' => 1,
	'max_status'       => false,
	'image_size'       => 'thumbnail',
];

$data = [
	'id'             => 'dutch-projects',
	'title'          => __( 'Nederlandse projecten', 'siw' ),
	'settings_pages' => 'dutch-projects',
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

return $data;
