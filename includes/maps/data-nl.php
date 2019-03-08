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

	/** Projecten */
	$language = apply_filters( 'wpml_current_language', NULL ); //TODO: verplaatsen naar SIW_i18n
	
	$provinces = siw_get_dutch_provinces();

	$projects = siw_get_option('dutch_projects');
		
	foreach ( $projects as $project ) {

		//TODO: wp_parse_args
		$work_type = siw_get_work_type( $project['work_type'] );
		$province_name = $provinces[ $project['province'] ];

		$duration = SIW_Formatting::format_date_range( date('Y-m-d', $project['start_date']['timestamp'] ), date('Y-m-d', $project['end_date']['timestamp'] ) );
		$description = [
			sprintf( __( 'Data: %s', 'siw' ), $duration ),
			sprintf( __( 'Deelnemers: %s', 'siw' ), $project['participants'] ),
			sprintf( __( 'Soort werk: %s', 'siw' ), $work_type->get_name() ),	//TODO: check op work_type
		];
		if ( isset( $project['local_fee'] ) ) {
			$description[] = sprintf( __( 'Lokale bijdrage: %s', 'siw' ), SIW_Formatting::format_amount( $project['local_fee'] ) );
		}
		$description[] = sprintf( __( 'Projectcode: %s', 'siw' ), $project['code'] );
		$description[] = sprintf( __( 'Locatie: %s, provincie %s', 'siw' ), $project['city'], $province_name );

		$location = [
			'id'            => sanitize_title( $project['code'] ),
			'title'         => $project["name_{$language}"],
			'image'         => isset( $project['image'] ) ? wp_get_attachment_url( $project['image'][0] ) : null,
			'about'         => $province_name,
			'lat'           => $project['latitude'] ?? null,
			'lng'           => $project['longitude'] ?? null,
			'description'   => SIW_Formatting::array_to_text( $description, BR ),
			'pin'           => 'circular pin-md pin-label',
			'category'      => 'nl',
			'fill'          => SIW_Properties::SECONDARY_COLOR,
		];
		$map_data['locations'][] = $location;

		// Provincies bijhouden t.b.v. inline css
		$provinces[] = sprintf( '#nl-%s path', $project['province'] );
	}

	/** Inline CSS */
	$provinces = array_unique( $provinces );
	$selectors = implode( ',', $provinces );

	$inline_css = [
		$selectors => [
			'fill' => SIW_Properties::PRIMARY_COLOR_HOVER,
		],
	];
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
