<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Mapplic-kaarten
 *
 * @author      Maarten Bruna
 * @package     SIW\Data
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
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