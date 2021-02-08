<?php declare(strict_types=1);

/**
 * Sustainable Development Goals
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Sustainable_Development_Goal;

/**
 * Geeft een array van Sustainable Development Goals terug
 * 
 * @since     3.1.0
 *
 * @return Sustainable_Development_Goal[]
 */
function siw_get_sustainable_development_goals( string $return = 'objects' ) : array {
	
	$goals = wp_cache_get( "{$return}", 'siw_sustainable_development_goals' );
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
		fn( $item ) => new Sustainable_Development_Goal( $item ),
		$data
	);

	if ( 'array' == $return ) {
		$goals = array_map(
			fn( Sustainable_Development_Goal $goal ) => $goal->get_full_name(),
			$goals
		);
	}
	wp_cache_set( "{$return}", $goals, 'siw_sustainable_development_goals' );
	return $goals;
}

/**
 * Geeft SDG terug
 * 
 * @since     3.1.0
 *
 * @param string $goal
 *
 * @return Sustainable_Development_Goal|null
 */
function siw_get_sustainable_development_goal( string $goal ) : ?Sustainable_Development_Goal {
	$goals = siw_get_sustainable_development_goals();
	return $goals[$goal] ?? null;
}