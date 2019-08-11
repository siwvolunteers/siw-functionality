<?php
/**
 * Functies m.b.t. talen
 * 
 * @author    Maarten Bruna
 * @package   SIW\Functions
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Geeft array met gegevens van talen terug
 *
 * @param string $index
 * @param string $context all|volunteer|project
 * @return SIW_Data_Language[]
 */
function siw_get_languages( string $context = 'all', string $index = 'slug' ) {
	$languages = wp_cache_get( "{$context}_{$index}", 'siw_languages' );

	if ( false !== $languages ) {
		return $languages;
	}

	$data = siw_get_data( 'languages' );

	foreach ( $data as $item ) {
		$language = new SIW_Data_Language( $item );
		if ( 'all' == $context 
			|| ( 'volunteer' == $context && true === $language->is_volunteer_language() )
			|| ( 'project' == $context && true === $language->is_project_language() )
		) {
			$languages[ $item[ $index ] ] = $language;
		}
	}
	wp_cache_set( "{$context}_{$index}", $languages, 'siw_languages' );
	
	return $languages;
}

/**
* Geeft informatie over een taal terug
*
* @return SIW_Data_Language
*/
function siw_get_language( string $language, string $index = 'slug' ) {

	$languages = siw_get_languages( 'all', $index );

	if ( isset( $languages[ $language ] ) ) {
		return $languages[ $language ];
	}

	return false;
}

/**
 * Geeft een array met niveau's van taalvaardigheid terug
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
