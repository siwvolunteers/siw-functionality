<?php
/**
 * Kaart van Nederland met Nederlandse projecten
 * 
 * @package SIW\Maps
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

siw_register_map( 'nl', __( 'Nederland', 'siw' ), 'netherlands' );

add_filter( 'siw_map_nl_data', function( $map_data ) {
	/** Standaard-categorie */
	$map_data['categories'] = [
		[
			'id'    => 'nl',
			'title' =>  __( 'Nederlandse projecten', 'siw' ),
			'show'  => true,
		],
	];

	/** Projecten */
	$projects = siw_get_dutch_projects();
	foreach ( $projects as $index => $project ) {
		$duration = SIW_Formatting::format_date_range( $project['start_date'], $project['end_date'] );
		$description =
			__( 'Data:', 'siw' ) . SPACE . $duration . BR .
			__( 'Deelnemers:', 'siw' ) . SPACE . $project['participants'] . BR .
			__( 'Soort werk:', 'siw' ) . SPACE . $project['work_name'] . BR .
			__( 'Locatie:', 'siw' ) . SPACE . $project['city'] . ', ' . __( 'provincie', 'siw' ) . SPACE . $project['province_name'];

		$location = [
			'id'            => $index,
			'title'         => $project['name'],
			'about'         => $project['province_name'],
			'lat'           => $project['latitude'],
			'lng'           => $project['longitude'],
			'description'   => $description,
			'pin'           => 'circular pin-md pin-label',
			'category'      => 'nl',
			'fill'          => SIW_Properties::get('secondary_color'),
		];
		$map_data['locations'][] = $location;

		// Provincies bijhouden t.b.v. inline css
		$provinces[] = sprintf( '#nl-%s path', $project['province'] );
	}

	/** Inline CSS */
	$provinces = array_unique( $provinces );
	$selectors = implode( ',', $provinces );

	$inline_css = array(
		$selectors => array(
			'fill' => SIW_Properties::get('primary_color_hover'),
		),
	);
	$map_data['inline_css'] = $inline_css;

	/** Gegevens kaart */
	$map_data['data'] = [
		'mapwidth'  => 600,
		'mapheight' => 600,
		'bottomLat' => '50.67500192979909',
		'leftLng'   => '2.8680356443589807',
		'topLat'    => '53.62609096857893',
		'rightLng'  => '7.679884929662812',
	];
 
	return $map_data;
});
