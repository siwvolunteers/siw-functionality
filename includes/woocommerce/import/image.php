<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Selecteer op basis van land en werk een projectafbeelding
 *
 * @param string $country
 * @param string $work
 *
 * @return string
 */
function siw_get_workcamp_image_file( $country, $work ) {
 // TODO: Coderedundatie verminderen
	$base_directory = WP_CONTENT_DIR . '/uploads/wpallimport/files/';

	$continent = siw_get_workcamp_continent_slug( $country );
	$country = siw_get_workcamp_country_slug( $country );
	$work_array = siw_get_workcamp_work_slugs( $work, false );
	$work_array = array_filter( $work_array );

	$url = '';

	if ( empty( $continent ) ) {
		return;
	}

	foreach ( $work_array as $work ) {
		$relative_directory = $continent . '/' . $work . '/' . $country;
		$dir = $base_directory . $relative_directory;
		if ( file_exists( $dir ) ) {
			$files = array_diff( scandir( $dir ), array( '.', '..', 'Thumbs.db' ) );
			$files = array_filter( $files, 'siw_is_file' );
			if ( sizeof( $files ) > 0 ) {
				$random_image = array_rand( $files, 1 );
				$filename = $files[ $random_image ];
				$url = $relative_directory . '/' . $filename;
				break;
			}
		}
	}
	if ( '' == $url ) {
		foreach ( $work_array as $work ) {
			$relative_directory = $continent.'/'.$work;
			$dir = $base_directory . $relative_directory;
			if ( file_exists( $dir ) ) {
				$files = array_diff( scandir( $dir ), array( '.', '..', 'Thumbs.db' ) );
				$files = array_filter( $files, 'siw_is_file' );

				if ( sizeof( $files ) > 0 ) {
					$random_image = array_rand( $files, 1 );
					$filename = $files[ $random_image ];
					$url = $relative_directory . '/' . $filename;
					break;
				}
			}
		}
	}
	if ( '' == $url ) {
		$relative_directory = $continent;
		$dir = $base_directory . $relative_directory;
		if ( file_exists( $dir ) ) {
			$files = array_diff( scandir( $dir ), array( '.', '..', 'Thumbs.db' ) );
			$files = array_filter( $files, 'siw_is_file' );
			if ( sizeof( $files ) > 0 ) {
				$random_image = array_rand( $files, 1 );
				$filename = $files[ $random_image ];
				$url = $relative_directory . '/' . $filename;
			}
		}
	}
	return $url;
}


/**
 * Bepaalt of URI een bestand is
 *
 * @param string $name
 *
 * @return bool
 */
function siw_is_file( $name ) {
	$is_file = false;
	if ( ( strpos( $name, '.' ) ) !== false ) {
		$is_file = true;
	}
	return $is_file;
}


/**
 * Genereer title voor projectafbeelding
 *
 * @param  string $work
 * @param  string $country
 *
 * @return string
 */
function siw_get_workcamp_image_title( $country, $work ) {
	return siw_get_workcamp_title( $country, $work );
}


/**
 * Genereer caption voor projectafbeelding
 *
 * @param  string $work
 * @param  string $country
 *
 * @return string
 */
function siw_get_workcamp_image_caption( $country, $work ) {
	return siw_get_workcamp_title( $country, $work );
}


/**
 * Genereer alt text voor projectafbeelding
 *
 * @param  string $work
 * @param  string $country
 *
 * @return string
 */
function siw_get_workcamp_image_alt_text( $country, $work) {
	return siw_get_workcamp_title( $country, $work );
}


/**
 * Genereer beschrijving voor projectafbeelding
 *
 * @param  string $work
 * @param  string $country
 *
 * @return string
 */
function siw_get_workcamp_image_description( $country, $work) {
	return siw_get_workcamp_title( $country, $work );
}
