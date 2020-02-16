<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor datums
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'id'             => 'dates',
	'title'          => __( 'Datums', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'dates',
	'fields'         => [
		[
			'type'     => 'heading',
			'name'     => __( 'Infodagen', 'siw' ),
		],
		[
			'id'         => 'info_days',
			'type'       => 'date',
			'tab'        => 'info_day',
			'clone'      => true,
			'add_button' => __( 'Infodag toevoegen', 'siw' ),
		],
		[
			'type'     => 'heading',
			'name'     => __( 'ESC-deadlines', 'siw' ),
		],
		[
			'id'         => 'esc_deadlines',
			'type'       => 'date',
			'tab'        => 'info_day',
			'clone'      => true,
			'add_button' => __( 'ESC-deadline toevoegen', 'siw' ),
		],
	],
];

return $data;
