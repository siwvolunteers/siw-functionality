<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;

/**
 * Opties voor jaarverslagen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'annual_reports',
	'title'          => __( 'Jaarverslagen', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'annual_reports',
	'fields'         => [
		[
			'id'            => 'annual_reports',
			'type'          => 'group',
			'clone'         => true,
			'sort_clone'    => true,
			'max_clone'     => Properties::MAX_ANNUAL_REPORTS,
			'collapsible'   => true,
			'default_state' => 'collapsed',
			'group_title'   => [ 'field' => 'year'],
			'add_button'    => __( 'Jaarverslag toevoegen', 'siw' ),
			'fields'        => [
				[
					'id'       => 'year',
					'name'     => __( 'Jaar', 'siw' ),
					'type'     => 'number',
					'required' => true,
					'min'      => intval( date( 'Y', strtotime( Properties::FOUNDING_DATE ) ) ),
					'max'      => intval(date( 'Y' ) )
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
