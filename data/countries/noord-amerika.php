<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Noord-Amerika
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'plato_code'  => 'CAN',
		'iso_code'    => 'ca',
		'slug'        => 'canada',
		'name'        => __( 'Canada', 'siw' ),
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'GRL',
		'iso_code'    => 'gl',
		'slug'        => 'groenland',
		'name'        => __( 'Groenland', 'siw' ),
		'continent'   => 'noord-amerika',
		'workcamps'   => true,
		'tailor_made' => false,
	],
	[
		'plato_code'  => 'USA',
		'iso_code'    => 'us',
		'slug'        => 'verenigde-staten',
		'name'        => __( 'Verenigde Staten', 'siw' ),
		'continent'   => 'noord-amerika',
		'workcamps'   => true,
		'tailor_made' => false,
	],
];

return $data;