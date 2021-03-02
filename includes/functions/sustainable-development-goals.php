<?php declare(strict_types=1);

/**
 * Sustainable Development Goals
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Sustainable_Development_Goal;

/**
 * Geeft een array van Sustainable Development Goals terug
 * @return Sustainable_Development_Goal[]
 */
function siw_get_sustainable_development_goals() : array {
	
	$goals = wp_cache_get( __FUNCTION__ );
	if ( false !== $goals ) {
		return $goals;
	}

	//Data ophalen en sorteren
	$data = siw_get_data( 'sustainable-development-goals' );
	$data = wp_list_sort( $data, 'name' );

	//Gebruik iso als index van array
	$data = array_column( $data , null, 'slug' );

	//Creëer objecten
	$goals = array_map(
		fn( array $item ) : Sustainable_Development_Goal => new Sustainable_Development_Goal( $item ),
		$data
	);

	wp_cache_set( __FUNCTION__, $goals );
	return $goals;
}

/** Geeft SDG terug */
function siw_get_sustainable_development_goal( string $goal ) : ?Sustainable_Development_Goal {
	$goals = siw_get_sustainable_development_goals();
	return $goals[$goal] ?? null;
}
