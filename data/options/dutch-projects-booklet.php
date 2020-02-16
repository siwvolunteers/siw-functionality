<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. Nederlandse Projecten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'dutch-projects-booklet',
	'title'          => __( 'Programmaboekje', 'siw' ),
	'settings_pages' => 'dutch-projects',
	'context'        => 'side',
	'fields'         => [
		[
			'id'   => 'dutch_projects_booklet_year',
			'name' => __( 'Jaar', 'siw' ),
			'type' => 'number',
			'min'  => intval( date( 'Y' ) ) - 10,
			'max'  => intval( date( 'Y' ) ) + 1,
		],
		[
			'id'               => 'dutch_projects_booklet',
			'name'             => __( 'Bestand', 'siw' ),
			'type'             => 'file_advanced',
			'max_file_uploads' => 1,
			'mime_type'        => 'application/pdf',
			'force_delete'     => false,
		],
	],
];

return $data;
