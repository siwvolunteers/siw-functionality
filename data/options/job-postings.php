<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties t.b.v. vacatures
 * 
 * @package   SIW\Data
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'job_postings',
	'title'          => __( 'Vacatures', 'siw' ),
	'settings_pages' => 'job-postings',
	'fields'         => [
		[
			'type'     => 'heading',
			'name'     => __( 'Vacaturetekst', 'siw' ),
		],
		[
			'id'       => 'job_postings_organization_profile',
			'name'     => __( 'Wie zijn wij', 'siw' ),
			'type'     => 'wysiwyg',
			'raw'      => true,
			'options'  => [
				'teeny'         => false,
				'media_buttons' => false,
				'dfw'           => false,
				'textarea_rows' => 5,
			],
		],
	],
];

return $data;
