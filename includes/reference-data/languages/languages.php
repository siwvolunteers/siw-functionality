<?php
/**
 * Functies m.b.t. talen
 * 
 * @author    Maarten Bruna
 * @package   SIW\Reference-Data
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 */

require_once( __DIR__ . '/class-siw-language.php' );

/**
 * Geeft array met gegevens van talen terug
 *
 * @param string $index
 * @param string $context all|volunteer|project
 * @return SIW_Language[]
 */
function siw_get_languages( $context = 'all', $index = 'slug' ) {

	$data = require SIW_DATA_DIR . '/languages.php';

	foreach ( $data as $item ) {
		$language = new SIW_Language( $item );
		if ( 'all' == $context 
			|| ( 'volunteer' == $context && true == $language->is_volunteer_language() )
			|| ( 'project' == $context && true == $language->is_project_language() )
		) {
			$languages[ $item[ $index ] ] = $language;
		}
	}
	return $languages;
}

/**
* Geeft informatie over een taal terug
*
* @return SIW_Language
*/
function siw_get_language( $language, $index = 'slug' ) {

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