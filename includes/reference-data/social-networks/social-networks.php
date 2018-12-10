<?php
/**
 * Sociale netwerken
 * 
 * @author      Maarten Bruna
 * @package 	SIW\Reference-Data
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-social-network.php' );
require_once( __DIR__ . '/data.php' );


/**
 * Undocumented function
 *
 * @param string $context all|share|follow
 * @return SIW_Social_Network[]
 */
function siw_get_social_networks( $context = 'all' ) {

	$data = [];

	/**
	 * Gegevens van sociale netwerken
	 *
	 * @param $data Gegevens van social netwerk {slug|name|follow|follow_url|share|share_url}
	 */
	$data = apply_filters( 'siw_social_network_data', $data );

	$social_networks = [];
	foreach ( $data as $item ) {
		$social_network = new SIW_Social_Network( $item );
		if ( 'all' == $context
		|| ( 'share' == $context && true == $social_network->is_for_sharing() )
		|| ( 'follow' == $context && true == $social_network->is_for_following() )
		) {
			$social_networks[ $item[ 'slug' ] ] = $social_network;
		}
	}

	return $social_networks;
}
