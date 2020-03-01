<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mapplic-kaarten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'id'    => 'nl',
		'name'  => __( 'Nederland', 'siw' ),
		'class' => 'Netherlands',
	],
	[
		'id'    => 'destinations',
		'name'  => __( 'Bestemmingen', 'siw' ),
		'class' => 'Destinations',
	],
	[
		'id'    => 'esc',
		'name'  => __( 'ESC', 'siw' ),
		'class' => 'ESC',
	],
];

return $data;