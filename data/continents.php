<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van continenten
 *
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'slug'  => 'europa',
		'name'  => __( 'Europa', 'siw' ),
		'color' => SIW_Properties::COLOR_EUROPE,
	],
	[
		'slug'  => 'azie',
		'name'  => __( 'Azië', 'siw' ),
		'color' => SIW_Properties::COLOR_ASIA,
	],
	[
		'slug'  => 'afrika',
		'name'  => __( 'Afrika', 'siw' ),
		'color' => SIW_Properties::COLOR_AFRICA,
	],
	[
		'slug'  => 'latijns-amerika',
		'name'  => __( 'Latijns-Amerika', 'siw' ),
		'color' => SIW_Properties::COLOR_LATIN_AMERICA,
	],
	[
		'slug'  => 'noord-amerika',
		'name'  => __( 'Noord-Amerika', 'siw' ),
		'color' => SIW_Properties::COLOR_NORTH_AMERICA,
	],
];

return $data;