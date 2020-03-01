<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor organisatie-gegevens
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$days = siw_get_days();

/* Reguliere openingstijden */
$opening_hours_fields = [];
foreach ( $days as $slug => $name ) {
	$day_fields = [
		'id'    => "day_{$slug}",
		'type'  => 'group',
		'fields' => [
			[
				'type'     =>'custom_html',
				'std'      => $name,
				'columns'  => 2,
			],
			[
				'id'        => 'open',
				'type'      => 'switch',
				'on_label'  => __( 'Geopend', 'siw' ),
				'off_label' => __( 'Gesloten', 'siw' ),
				'columns'   => 2,
			],
			[
				'id'       => 'opening_time',
				'type'     => 'time',
				'columns'  => 4,
				'prepend'  => __('Van', 'siw' ),
				'required' => true,
				'visible'  => [ "opening_hours[day_{$slug}][open]", true ],
			],
			[
				'id'       => 'closing_time',
				'type'     => 'time',
				'columns'  => 4,
				'prepend'  => __('Tot', 'siw' ),
				'required' => true,
				'visible'  => [ "opening_hours[day_{$slug}][open]", true ],
			],
		],
	];
	array_push( $opening_hours_fields, $day_fields );
}

/* Afwijkende openingstijden */
$special_opening_hours_fields = [
	[
		'id'        => 'date',
		'type'      => 'date',
		'columns'   => 2,
	],
	[
		'id'        => 'opened',
		'type'      => 'switch',
		'on_label'  => __( 'Geopend', 'siw' ),
		'off_label' => __( 'Gesloten', 'siw' ),
		'columns'   => 2,
	],
	[
		'id'       => 'opening_time',
		'type'     => 'time',
		'columns'  => 4,
		'prepend'  => __( 'Van', 'siw' ),
		'required' => true,
		'visible'  => [ 'opened', true ],
	],
	[
		'id'       => 'closing_time',
		'type'     => 'time',
		'columns'  => 4,
		'prepend'  => __( 'Tot', 'siw' ),
		'required' => true,
		'visible'  => [ 'opened', true ],
	],
];


$data = [
	'id'             => 'opening_hours',
	'title'          => __( 'Openingstijden', 'siw' ),
	'settings_pages' => 'settings',
	'tab'            => 'opening_hours',
	'fields'         => [
		[
			'type'   => 'heading',
			'name'   => __( 'Reguliere openingstijden', 'siw' ),
		],
		[
			'id'     => 'opening_hours',
			'type'   => 'group',
			'fields' => $opening_hours_fields,
		],
		[
			'type'   => 'heading',
			'name'   => __( 'Afwijkende openingstijden', 'siw' ),
		],
		[
			'id'     => 'special_opening_hours',
			'type'   => 'group',
			'clone'  => true,
			'fields' => $special_opening_hours_fields,
		],
	],
];

return $data;
