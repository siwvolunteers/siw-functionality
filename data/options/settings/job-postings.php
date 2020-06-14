<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. vacatures
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'job_postings',
	'title'          => __( 'Vacatures', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'job_postings',
	'fields'         => [
		[
			'type'     => 'heading',
			'name'     => __( 'Vacaturetekst', 'siw' ),
		],
		[
			'id'       => 'job_postings_organization_profile',
			'name'     => __( 'Wie zijn wij', 'siw' ),
			'type'     => 'wysiwyg',
			'required' => true,
		],
		[
			'id'        => 'hr_manager',
			'type'      => 'group',
			'fields'    => [
				[
					'type'     => 'heading',
					'name'     => __( 'P&O manager', 'siw' ),
					'desc'     => __( 'Standaard contactpersoon voor sollicitaties', 'siw' ),
				],
				[
					'id'       => 'name',
					'name'     => __( 'Naam', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'title',
					'name'     => __( 'Functie', 'siw' ),
					'type'     => 'text',
					'required' => true,
				],
				[
					'id'       => 'email',
					'name'     => __( 'E-mail', 'siw' ),
					'type'     => 'email',
					'required' => true,
				],
			],
		],
	],
];

return $data;
