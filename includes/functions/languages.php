<?php declare(strict_types=1);

use SIW\Data\Language;

/**
 * Geeft array met gegevens van talen terug
 *
 * @return Language[]
 */
function siw_get_languages( string $index = Language::SLUG ): array {
	$languages = wp_cache_get( $index, __FUNCTION__ );

	if ( false !== $languages ) {
		return $languages;
	}

	// Data ophalen en sorteren
	$data = siw_get_data( 'languages' );
	$data = wp_list_sort( $data, 'name' );

	// Zet index van array
	$data = array_column( $data, null, $index );

	// Creëer objecten
	$languages = array_map(
		fn( array $item ): Language => new Language( $item ),
		$data
	);

	wp_cache_set( $index, $languages, __FUNCTION__ );

	return $languages;
}

/** Geeft lijst van talen terug */
function siw_get_languages_list( string $index = Language::SLUG ): array {
	return array_map(
		fn( Language $language ): string => $language->get_name(),
		siw_get_languages( $index )
	);
}

/** Geeft informatie over een taal terug */
function siw_get_language( string $language, string $index = Language::SLUG ): ?Language {
	$languages = siw_get_languages( $index );
	return $languages[ $language ] ?? null;
}
