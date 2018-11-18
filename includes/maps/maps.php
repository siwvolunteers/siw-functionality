<?php
/**
 * Functies t.b.v. Mapplic-kaarten
 * 
 * @package SIW\Maps
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( __DIR__ . '/class-siw-map.php' );
require_once( __DIR__ . '/data-destinations.php' );
require_once( __DIR__ . '/data-evs.php' );
require_once( __DIR__ . '/data-nl.php' );

/**
 * Toon een Mapplic-kaart
 *
 * @param string $id
 * @return void
 */
function siw_render_map( $id ) {

	$map_ids = [];
	/**
	 * ID's van beschikbare kaarten
	 *
	 * @param array $map_ids ID's van de beschikbare karten
	 */
	$map_ids = apply_filters( 'siw_maps', $map_ids );
	if ( ! array_key_exists( $id, $map_ids ) ) {
		return false;
	}

	$map_files = [];
	/**
	 * Bestandsnamen van beschikbare kaarten
	 *
	 * @param array $map_files Bestandsnamen van beschikbare kaarten
	 */
	$map_files = apply_filters( 'siw_map_files', $map_files );
	if ( ! array_key_exists( $id, $map_files ) ) {
		return false;
	}

	$map_data = [
		'data'			=> [],
		'options'		=> [],
		'categories'	=> [],
		'locations'		=> [],
		'inline_css'	=> [],
	];
	/**
	 * Gegevens van kaart
	 *
	 * @param array $map_data Gegevens van kaart {data|options|categories|locations|inline_css}
	 */
	$map_data = apply_filters( "siw_map_{$id}_data", $map_data );

	/* Genereer kaart */
	$map = new SIW_Map;
	$map->set_id( $id );
	$map->set_filename( $map_files[ $id ] );
	$map->set_categories( $map_data['categories'] );
	$map->set_locations( $map_data['locations'] );
	$map->set_inline_css( $map_data['inline_css'] );
	$map->set_data( $map_data['data'] );
	$map->set_options( $map_data['options'] );
	
	return $map->render();
}

/**
 * Voeg een kaart toe
 *
 * @param string $id
 * @param string $name
 * @param string $file
 * @return void
 */
function siw_register_map( $id, $name, $file ) {
	add_filter( 'siw_maps', function( $maps ) use( $id, $name ) {
		$maps[ $id ] = $name;
		return $maps;
	});
	add_filter( 'siw_map_files', function( $map_files ) use( $id, $file ) {
		$map_files[ $id ] = $file;
		return $map_files;
	});
}

