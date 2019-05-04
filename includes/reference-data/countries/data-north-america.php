<?php
/**
 * Gegevens van landen in Noord-Amerika
 * 
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'siw_country_data', function( $data ) {
	$data[ 'noord-amerika' ] = [
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
});