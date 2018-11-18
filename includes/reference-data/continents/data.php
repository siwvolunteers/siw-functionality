<?php
/**
 * Data van continenten
 *
 * @author      Maarten Bruna
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'siw_continent_data', function( $data) {

	$data = [
		[
			'slug'  => 'europa',
			'name'  => __( 'Europa', 'siw' ),
			'color' => SIW_COLOR_EUROPE,
		],
		[
			'slug'  => 'azie',
			'name'  => __( 'Azië', 'siw' ),
			'color' => SIW_COLOR_ASIA,
		],
		[
			'slug'  => 'afrika',
			'name'  => __( 'Afrika', 'siw' ),
			'color' => SIW_COLOR_AFRICA,
		],
		[
			'slug'  => 'latijns-amerika',
			'name'  => __( 'Latijns-Amerika', 'siw' ),
			'color' => SIW_COLOR_LATIN_AMERICA,
		],
		[
			'slug'  => 'noord-amerika',
			'name'  => __( 'Noord-Amerika', 'siw' ),
			'color' => SIW_COLOR_NORTH_AMERICA,
		],
	];

	return $data;
});