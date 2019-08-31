<?php
/**
 * Sociale netwerken
 * 
 * @author    Maarten Bruna
 * @package   SIW\Functions
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Geeft een array van sociale netwerken terug
 *
 * @param string $context all|share|follow
 * @return SIW_Data_Social_Network[]
 */
function siw_get_social_networks( $context = 'all' ) {

	$social_networks = wp_cache_get( "{$context}", 'siw_social_networks' );
	if ( false !== $social_networks ) {
		return $social_networks;
	}

	$data = siw_get_data( 'social-networks' );

	$social_networks = [];
	foreach ( $data as $item ) {
		$social_network = new SIW_Data_Social_Network( $item );
		if ( 'all' == $context
		|| ( 'share' == $context && true === $social_network->is_for_sharing() )
		|| ( 'follow' == $context && true === $social_network->is_for_following() )
		) {
			$social_networks[ $item[ 'slug' ] ] = $social_network;
		}
	}

	wp_cache_set( "{$context}", $social_networks, 'siw_social_networks' );

	return $social_networks;
}