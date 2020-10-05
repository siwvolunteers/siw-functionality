<?php declare(strict_types=1);

/**
 * Functies m.b.t. soorten werk
 * 
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Work_Type;

/**
 * Geeft een array met soort werk voor projecten terug
 * 
 * @since     3.0.0
 *
 * @param string $index
 * @param string $context all|dutch_projects|tailor_made_projects
 * @param string $return objects|array
 * 
 * @return array
 */
function siw_get_work_types( string $context = 'all', string $index = 'slug', string $return = 'objects' ) : array {
	$work_types = wp_cache_get( "{$context}_{$index}_{$return}", 'siw_work_types' );
	if ( false !== $work_types ) {
		return $work_types;
	}

	//Data ophalen en sorteren
	$data = siw_get_data( 'work-types' );
	$data = wp_list_sort( $data, 'name' );

	//Zet index van array
	$data = array_column( $data , null, $index );

	//Creëer objecten
	$work_types = array_map(
		fn( $item ) => new Work_Type( $item ),
		$data
	);

	//Filter op context
	$work_types = array_filter(
		$work_types,
		function( $work_type ) use ( $context ) {
			return ( 'all' == $context
				|| ( 'dutch_projects' == $context && $work_type->is_for_dutch_projects() ) 
				|| ( 'tailor_made_projects' == $context && $work_type->is_for_tailor_made_projects() )
			);
		}
	);
	if ( 'array' == $return ) {
		$work_types = array_map(
			fn( Work_Type $work_type ) => $work_type->get_name(),
			$work_types
		);
	}
	wp_cache_set( "{$context}_{$index}_{$return}", $work_types, 'siw_work_types' );

	return $work_types;
}

/**
 * Geeft informatie over soort werk terug
 * 
 * @since     3.0.0
 *
 * @param string $work_type
 * @param string $index
 * @return Work_Type
 */
function siw_get_work_type( string $work_type, string $index = 'slug' ) : ?Work_Type {
	$work_types = siw_get_work_types( 'all', $index );
	return $work_types[ $work_type ] ?? null;
}
