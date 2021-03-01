<?php declare(strict_types=1);

/**
 * Functies m.b.t. talen
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */

use SIW\Data\Language;

/**
 * Geeft array met gegevens van talen terug
 * 
 * @since     3.0.0
 *
 * @param string $context all|volunteer|project
 * @param string $index
 * @param string $return
 *
 * @return array
 */
function siw_get_languages( string $context = 'all', string $index = 'slug', string $return = 'objects' ) : array {
	$languages = wp_cache_get( "{$context}_{$index}_{$return}", 'siw_languages' );

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
		fn( array $item ) : Language => new Language( $item ),
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

	if ( 'array' == $return ) {
		$languages = array_map(
			fn( Language $language ) : string => $language->get_name(),
			$languages
		);
	}

	wp_cache_set( "{$context}_{$index}_{$return}", $languages, 'siw_languages' );

	return $languages;
}

/** Geeft lijst van talen terug */
function siw_get_languages_list( string $context = 'all', string $index = 'slug' ) : array {
	return array_map(
		fn( Language $language ) : string => $language->get_name(),
		siw_get_languages( $context, $index )
	);
}

/** Geeft informatie over een taal terug */
function siw_get_language( string $language, string $index = 'slug' ) : ?Language {
	$languages = siw_get_languages( 'all', $index );
	return $languages[ $language ] ?? null;
}

/** Geeft een lijst met niveau's van taalvaardigheid terug */
function siw_get_language_skill_levels() : array {
	$language_skill_levels = [
		'1' => __( 'Matig', 'siw' ),
		'2' => __( 'Redelijk', 'siw' ),
		'3' => __( 'Goed', 'siw' ),
		'4' => __( 'Uitstekend', 'siw' ),
	];
	return $language_skill_levels;
}
