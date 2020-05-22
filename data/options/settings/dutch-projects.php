<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;

/**
 * Opties voor Nederlandse projecten
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'dutch_projects',
	'title'          => __( 'Nederlandse projecten', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'dutch_projects',
	'fields'         => [
		[
			'id'            => 'dutch_projects_booklet',
			'type'          => 'group',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => 5,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => 'Programmaboekje {year}',
			'add_button'    => __( 'Programmaboekje toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'year',
					'name'     => __( 'Jaar', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => intval( date( 'Y', strtotime( Properties::FOUNDING_DATE ) ) ),
					'max'      => intval( date( 'Y' ) )
				],
				[
					'id'               => 'file',
					'name'             => __( 'Bestand', 'siw' ),
					'type'             => 'file_advanced',
					'required'         => true,
					'max_file_uploads' => 1,
					'mime_type'        => 'application/pdf',
					'force_delete'     => false,
				],
			]
		]
	],
];

return $data;
