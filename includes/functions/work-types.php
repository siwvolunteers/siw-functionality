<?php declare(strict_types=1);

/**
 * Functies m.b.t. soorten werk
 * 
 * @copyright 2019-2020 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Work_Type;

/** Geeft een array met informatie over soorten werk voor projecten */
function siw_get_work_types( string $context = Work_Type::ALL, string $index = 'slug' ) : array {
	$work_types = wp_cache_get( "{$context}_{$index}", __FUNCTION__ );
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
		fn( array $item ) : Work_Type => new Work_Type( $item ),
		$data
	);

	//Filter op context
	$work_types = array_filter(
		$work_types,
		fn( Work_Type $work_type ) : bool => $work_type->is_valid_for_context( $context )
	);
	wp_cache_set( "{$context}_{$index}", $work_types, __FUNCTION__ );
	return $work_types;
}

/** Geeft lijst van types werk terug */
function siw_get_work_types_list( string $context = Work_Type::ALL, string $index = 'slug' ) : array {
	return array_map(
		fn( Work_Type $work_type ) : string => $work_type->get_name(),
		siw_get_work_types( $context, $index )
	);
}

/** Geeft informatie over soort werk terug */
function siw_get_work_type( string $work_type, string $index = 'slug' ) : ?Work_Type {
	$work_types = siw_get_work_types( Work_Type::ALL, $index );
	return $work_types[ $work_type ] ?? null;
}
