<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties voor landen
 * 
 * @package   SIW\Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

$countries = siw_get_countries();
$continents = siw_get_continents();

foreach ( $continents as $continent ) {
	$tabs[ $continent->get_slug() ] = $continent->get_name();
}
foreach ( $countries as $country ) {
	if ( false == $country->is_allowed() ) {
		continue;
	}
	$continent_slug = $country->get_continent()->get_slug();

	if ( 'europa' == $continent_slug ) {
		$available_projects = [
			'workcamps'   => __( 'Groepsprojecten', 'siw' ),
			'esc'         => __( 'ESC', 'siw' ),
		];
	}
	else {
		$available_projects = [
			'workcamps'   => __( 'Groepsprojecten', 'siw' ),
			'tailor_made' => __( 'Projecten op maat', 'siw' ),
		];
	}

	$country_fields[] = [
		'name' => $country->get_name(),
		'type' => 'heading',
		'tab'  => $continent_slug,
	];
	$country_fields[] = [
		'id'          => $country->get_slug() . '_specialist',
		'name'        => __( 'Regiospecialist', 'siw' ),
		'type'        => 'user',
		'field_type'  => 'select_advanced',
		'tab'         => $continent_slug,
	];
	$country_fields[] = [
		'id'      => $country->get_slug() . '_available_projects',
		'type'    => 'checkbox_list',
		'name'    => __( 'Aanbod', 'siw' ),
		'tab'     => $continent_slug,
		'options' => $available_projects,
		'std'      => [
			$country->has_workcamps() ? 'workcamps' : '',
			$country->has_tailor_made_projects() ? 'tailor_made' : '',
			$country->has_esc_projects() ? 'esc' : '',
		],
		'disabled' => true,
		'readonly' => true,
	];
}

$data = [
	'id'             => 'countries',
	'title'          => __( 'Landen', 'siw' ),
	'tabs'           => $tabs,
	'tab_style'      => 'left',
	'settings_pages' => 'siw-options-countries',
	'fields'         => $country_fields,
];

return $data;
