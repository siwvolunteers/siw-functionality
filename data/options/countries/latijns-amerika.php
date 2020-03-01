<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor landen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$continent = siw_get_continent( 'latijns-amerika' );
$countries = $continent->get_countries();

foreach ( $countries as $country ) {
	if ( ! $country->is_allowed() ) {
		continue;
	}

	$available_projects = [
		'workcamps'   => __( 'Groepsprojecten', 'siw' ),
		'tailor_made' => __( 'Projecten op maat', 'siw' ),
	];

	$country_fields[] = [
		'id'            => $country->get_slug(),
		'type'          => 'group',
		'group_title'   => $country->get_name(),
		'collapsible'   => true,
		'default_state' => 'collapsed',
		'fields'        => [
			[
				'id'      => 'available_projects',
				'type'    => 'checkbox_list',
				'name'    => __( 'Aanbod', 'siw' ),
				'options' => $available_projects,
				'std'      => [
					$country->has_workcamps() ? 'workcamps' : '',
					$country->has_tailor_made_projects() ? 'tailor_made' : '',
				],
				'disabled' => true,
				'readonly' => true,
			],
		],
	];
}

$data = [
	'id'             => "countries_{$continent->get_slug()}",
	'title'          => $continent->get_name(),
	'settings_pages' => 'countries',
	'tab'            => $continent->get_slug(),
	'fields'         => $country_fields,
];

return $data;
