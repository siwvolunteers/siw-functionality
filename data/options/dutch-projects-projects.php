<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. Nederlandse Projecten
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

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
	'id'   => 'address',
	'name' => 'Address',
	'type' => 'text',
];
$group_fields[] = [
	'id'            => 'location',
	'name'          => __( 'Locatie', 'siw' ),
	'type'          => 'map',
	'std'           => '52.0907, 5.1214',
	'address_field' => 'address',
	'region'        => 'nl',
	'api_key'       => siw_get_option( 'google_maps_api_key'),
];
$group_fields[] = [
	'id'         => 'start_date',
	'name'       => __( 'Startdatum', 'siw' ),
	'type'       => 'date',
	'js_options' => [
		'dateFormat'      => 'yy-mm-dd',
		'showButtonPanel' => false,
	],
	'readonly'   => true,
];
$group_fields[] = [
	'id'         => 'end_date',
	'name'       => __( 'Einddatum', 'siw' ),
	'type'       => 'date',
	'js_options' => [
		'dateFormat'      => 'yy-mm-dd',
		'showButtonPanel' => false,
	],
	'readonly'   => true,
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
	'id'      => 'local_fee',
	'name'    => __( 'Lokale bijdrage', 'siw' ),
	'type'    => 'number',
	'prepend' => '€',
	'min'     => 1,
	'max'     => 999,
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
			'textarea_rows' => 5,
		],
	];
}
$group_fields[] = [
	'id'               => 'image',
	'name'             => __( 'Afbeelding', 'siw' ),
	'type'             => 'image_advanced',
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
