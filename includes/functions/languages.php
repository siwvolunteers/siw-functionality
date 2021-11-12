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
 * @return Language[]
 */
function siw_get_languages( string $context = Language::ALL, string $index = Language::SLUG ) : array {
	$languages = wp_cache_get( "{$context}_{$index}", __FUNCTION__ );

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
		fn( Language $language ) : bool => $language->is_valid_for_context( $context )
	);

	wp_cache_set( "{$context}_{$index}", $languages, __FUNCTION__ );

	return $languages;
}

/** Geeft lijst van talen terug */
function siw_get_languages_list( string $context = Language::ALL, string $index = Language::SLUG ) : array {
	return array_map(
		fn( Language $language ) : string => $language->get_name(),
		siw_get_languages( $context, $index )
	);
}

/** Geeft informatie over een taal terug */
function siw_get_language( string $language, string $index = Language::SLUG ) : ?Language {
	$languages = siw_get_languages( Language::ALL, $index );
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
