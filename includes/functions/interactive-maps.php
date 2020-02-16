<?php

/**
 * Functies m.b.t. interactive kaarten (Mapplic)
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Genereert interactieve kaart
 * 
 * @since     3.0.0
 *
 * @param string $id
 * @return string
 */
function siw_generate_interactive_map( string $id ) {
	$map_data = siw_get_data( 'interactive-maps' );
	foreach ( $map_data as $item ) {
		$maps[ $item['id'] ] = $item['class'];
	}
	if ( ! isset( $maps[ $id ] ) ) {
		return null;
	}
	$class = "\SIW\Elements\Interactive_Map_{$maps[ $id ]}";
	$map = new $class;
	return $map->generate();
}

/**
 * Geeft lijst met interactieve kaarten terug
 * 
 * @since     3.0.0
 * 
 * @return array
 */
function siw_get_interactive_maps() {
	$map_data = siw_get_data( 'interactive-maps' );
	foreach ( $map_data as $item ) {
		$maps[ $item['id'] ] = $item['name'];
	}
	return $maps;
}
