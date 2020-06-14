<?php

/**
 * Sociale netwerken
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

use SIW\Data\Social_Network;

/**
 * Geeft een array van sociale netwerken terug
 * 
 * @since     3.0.0
 *
 * @param string $context all|share|follow
 * @param string $return objects|array
 * 
 * @return Social_Network[]
 */
function siw_get_social_networks( $context = 'all', string $return = 'objects' ) : array {

	$social_networks = wp_cache_get( "{$context}_{$return}", 'siw_social_networks' );
	if ( false !== $social_networks ) {
		return $social_networks;
	}

	//Data ophalen en sorteren
	$data = siw_get_data( 'social-networks' );
	$data = wp_list_sort( $data, 'name' );

	//Gebruik slug als index van array
	$data = array_column( $data , null, 'slug' );

	//Creëer objecten
	$social_networks = array_map(
		function( $item ) {
			return new Social_Network( $item );
		},
		$data
	);

	//Filter op context
	$social_networks = array_filter(
		$social_networks, 
		function( $social_network ) use ( $context ) {
			return ( 'all' == $context
				|| ( 'share' == $context && $social_network->is_for_sharing() )
				|| ( 'follow' == $context && $social_network->is_for_following() )
			);
		}
	);
	if ( 'array' == $return ) {
		$social_networks = array_map(
			function( $social_network ) {
				return $social_network->get_name();
			},
			$social_networks
		);
	}
	wp_cache_set( "{$context}_{$return}", $social_networks, 'siw_social_networks' );

	return $social_networks;
}
