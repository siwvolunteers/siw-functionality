<?php declare(strict_types=1);

/**
 * Sociale netwerken
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Social_Network;
use SIW\Data\Social_Network_Context;

/**
 * Geeft een lijst met gegevens van sociale netwerken terug
 *
 * @return Social_Network[]
 */
function siw_get_social_networks( Social_Network_Context $context = Social_Network_Context::ALL ): array {

	$social_networks = wp_cache_get( $context->value, __FUNCTION__ );
	if ( false !== $social_networks ) {
		return $social_networks;
	}

	// Data ophalen en sorteren
	$data = siw_get_data( 'social-networks' );
	$data = wp_list_sort( $data, 'name' );

	// Gebruik slug als index van array
	$data = array_column( $data, null, 'slug' );

	// Creëer objecten
	$social_networks = array_map(
		fn( array $item ): Social_Network => new Social_Network( $item ),
		$data
	);

	// Filter op context
	$social_networks = array_filter(
		$social_networks,
		fn( Social_Network $social_network ): bool => $social_network->is_valid_for_context( $context )
	);
	wp_cache_set( $context->value, $social_networks, __FUNCTION__ );

	return $social_networks;
}

/** Geeft lijst van sociale netwerken terug */
function siw_get_social_networks_list( Social_Network_Context $context = Social_Network_Context::ALL ): array {
	return array_map(
		fn( Social_Network $social_network ): string => $social_network->get_name(),
		siw_get_social_networks( $context )
	);
}


/** Haalt gegevens van social network op (o.b.v. slug) */
function siw_get_social_network( string $slug ): ?Social_Network {
	$social_networks = siw_get_social_networks();
	return $social_networks[ $slug ] ?? null;
}
