<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van landen in Noord-Amerika
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'iso'         => 'CAN',
		'slug'        => 'canada',
		'name'        => __( 'Canada', 'siw' ),
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'ca', 'x' => 0.2271,'y' => 0.4290 ],
	],
	[
		'iso'         => 'GRL',
		'slug'        => 'groenland',
		'name'        => __( 'Groenland', 'siw' ),
		'continent'   => 'noord-amerika',
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'gl', 'x' => 0.3883,'y' => 0.1851 ],
	],
	[
		'iso'         => 'USA',
		'slug'        => 'verenigde-staten',
		'name'        => __( 'Verenigde Staten', 'siw' ),
		'continent'   => 'noord-amerika',
		'allowed'     => true,
		'workcamps'   => true,
		'tailor_made' => false,
		'world_map'   => [ 'code' => 'us', 'x' => 0.2338,'y' => 0.5232 ],
	],
];

return $data;