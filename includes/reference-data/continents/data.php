<?php
/**
 * Data van continenten
 *
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'siw_continent_data', function( $data) {

	$data = [
		[
			'slug'  => 'europa',
			'name'  => __( 'Europa', 'siw' ),
			'color' => SIW_Properties::get('color_europe'),
		],
		[
			'slug'  => 'azie',
			'name'  => __( 'Azië', 'siw' ),
			'color' => SIW_Properties::get('color_asia'),
		],
		[
			'slug'  => 'afrika',
			'name'  => __( 'Afrika', 'siw' ),
			'color' => SIW_Properties::get('color_africa'),
		],
		[
			'slug'  => 'latijns-amerika',
			'name'  => __( 'Latijns-Amerika', 'siw' ),
			'color' => SIW_Properties::get('color_latin_america'),
		],
		[
			'slug'  => 'noord-amerika',
			'name'  => __( 'Noord-Amerika', 'siw' ),
			'color' => SIW_Properties::get('color_north_america'),
		],
	];

	return $data;
});