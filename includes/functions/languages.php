<?php

/**
 * Functies m.b.t. talen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Language;

/**
 * Geeft array met gegevens van talen terug
 * 
 * @since     3.0.0
 *
 * @param string $index
 * @param string $context all|volunteer|project
 * @return Language[]
 */
function siw_get_languages( string $context = 'all', string $index = 'slug' ) {
	$languages = wp_cache_get( "{$context}_{$index}", 'siw_languages' );

	if ( false !== $languages ) {
		return $languages;
	}

	//Data ophalen en sorteren
	$data = siw_get_data( 'languages' );
	$data = wp_list_sort( $data, 'name' );

	//Zet index van array
	$data = array_column( $data , null, $index );

	//Creëer objecten
	$languages = array_map(
		function( $item ) {
			return new Language( $item );
		},
		$data
	);

	//Filter op context
	$languages = array_filter(
		$languages, 
		function( $language ) use ( $context ) {
			return ( 'all' == $context 
				|| ( 'volunteer' == $context && $language->is_volunteer_language() )
				|| ( 'project' == $context && $language->is_project_language() )
			);
		}
	);
	wp_cache_set( "{$context}_{$index}", $languages, 'siw_languages' );

	return $languages;
}

/**
 * Geeft informatie over een taal terug
 * 
 * @since     3.0.0
 *
 * @param string $language
 * @param string $index
 * @return Language
 */
function siw_get_language( string $language, string $index = 'slug' ) {
	$languages = siw_get_languages( 'all', $index );
	return $languages[ $language ] ?? false;
}

/**
 * Geeft een array met niveau's van taalvaardigheid terug
 * 
 * @since     3.0.0
 *
 * @return array
 */
function siw_get_language_skill_levels() {
	$language_skill_levels = [
		'1'	=> __( 'Matig', 'siw' ),
		'2'	=> __( 'Redelijk', 'siw' ),
		'3'	=> __( 'Goed', 'siw' ),
		'4'	=> __( 'Uitstekend', 'siw' ),
	];
	return $language_skill_levels;
}
