<?php
/**
 * Sociale netwerken
 * 
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-social-network.php' );

/**
 * Geeft een array van sociale netwerken terug
 *
 * @param string $context all|share|follow
 * @return SIW_Social_Network[]
 */
function siw_get_social_networks( $context = 'all' ) {

	$data = require SIW_DATA_DIR . '/social-networks.php';

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