<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor organisatie-gegevens
 * 
 * @package   SIW\Options
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

$data = [
	'id'             => 'annual_reports',
	'title'          => __( 'Jaarverslagen', 'siw' ),
	'settings_pages' => 'organization',
	'context'        => 'side',
	'fields'         => [
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
					'min'      => intval( date( 'Y', strtotime( SIW_Properties::FOUNDING_DATE ) ) ),
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
