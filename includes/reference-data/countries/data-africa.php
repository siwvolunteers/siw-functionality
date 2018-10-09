<?php
/**
 * Gegevens van landen in Afrika
 * 
 * @package 	SIW\Reference data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'siw_country_data', function( $data ) {

	$data['afrika'] = [
        [
		    'iso'       	=> 'BDI',
			'slug'			=> 'burundi',
			'name'			=> __( 'Burundi', 'siw' ),
			'allowed'		=> false,
			'workcamps'		=> false,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'bi', 'x' => 0.5794, 'y' => 0.7096 ],
		],
		[
			'iso'			=> 'BWA',
			'slug'			=> 'botswana',
			'name'			=> __( 'Botswana', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> true,
			'world_map'		=> [ 'code' => 'bw', 'x' => 0.5639, 'y' => 0.7901 ],
		],
		[
			'iso'			=> 'GHA',
			'slug'			=> 'ghana',
			'name'			=> __( 'Ghana', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'gh', 'x' => 0.4984, 'y' => 0.6602 ],
		],
		[
			'iso'			=> 'KEN',
			'slug'			=> 'kenia',
			'name'			=> __( 'Kenia', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> true,
			'world_map'		=> [ 'code' => 'ke', 'x' => 0.6000, 'y' => 0.6931 ],
		],
		[
			'iso'			=> 'MAR',
			'slug'			=> 'marokko',
			'name'			=> __( 'Marokko', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'ma', 'x' => 0.4834, 'y' => 0.5547 ],
		],
		[
			'iso'			=> 'SEN',
			'slug'			=> 'senegal',
			'name'			=> __( 'Senegal', 'siw' ),
			'allowed'		=> false,
			'workcamps'		=> false,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'sn', 'x' => 0.4623, 'y' => 0.6319 ],
		],
		[
			'iso'			=> 'TGO',
			'slug'			=> 'togo',
			'name'			=> __( 'Togo', 'siw' ),
			'allowed'		=> false,
			'workcamps'		=> true,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'tg', 'x' => 0.5038, 'y' => 0.6559 ],
		],
		[
			'iso'			=> 'TUN',
			'slug'			=> 'tunesie',
			'name'			=> __( 'Tunesië', 'siw' ),
			'allowed'		=> false,
			'workcamps'		=> false,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'tn', 'x' => 0.5264, 'y' => 0.5411 ],
		],
		[
			'iso'			=> 'TZA',
			'slug'			=> 'tanzania',
			'name'			=> __( 'Tanzania', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> true,
			'world_map'		=> [ 'code' => 'tz', 'x' => 0.5924, 'y' => 0.7217 ],
		],
		[
			'iso'			=> 'UGA',
			'slug'			=> 'uganda',
			'name'			=> __( 'Uganda', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> false,
			'world_map'		=> [ 'code' => 'ug', 'x' => 0.5863, 'y' => 0.6921 ],
		],
		[
			'iso'			=> 'ZAF',
			'slug'			=> 'zuid-afrika',
			'name'			=> __( 'Zuid-Afrika', 'siw' ),
			'allowed'		=> true,
			'workcamps'		=> true,
			'tailor_made'	=> true,
			'world_map'		=> [ 'code' => 'za', 'x' => 0.5654, 'y' => 0.8221 ],
		],
	];
    
    return $data;
} );