<?php
/**
 * Functies m.b.t. referentiegegevens
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

/**
 * Haalt data uit bestand
 * 
 * @since     3.0.0
 *
 * @param string $file
 * @return mixed
 */
function siw_get_data( string $file ) {
	$file = strtolower( str_replace( '_', '-', $file ) );

	$data_file = SIW_DATA_DIR . '/' . "{$file}.php";

	if ( ! file_exists( $data_file ) ) {
		return null;
	}
	$data = require $data_file;
	return $data;
}

/**
 * Wrapper om rwmb_meta
 * 
 * @since     3.0.0
 *
 * @param string $key
 * @param array $args
 * @param int $post_id
 * @return mixed
 */
function siw_meta( string $key, array $args = [], int $post_id = null ) {
	if ( function_exists( 'rwmb_meta' ) ) {
		return rwmb_meta( $key, $args, $post_id );
	}
	return null;
}

/**
 * Geeft data-file id's uit specifieke directory terug
 * 
 * @since     3.0.0
 *
 * @param string $directory
 * @return array
 */
function siw_get_data_file_ids( string $directory, bool $include_subdirectories = true ) {

	$base_directory = SIW_DATA_DIR . "/{$directory}";
	$files = glob( $base_directory . '/*.php' );
	if ( $include_subdirectories ) {
		$subdirectories = glob( $base_directory . '/*', GLOB_ONLYDIR );
		foreach ( $subdirectories as $subdirectory ) {
			$files = array_merge(
				$files,
				glob( $subdirectory . '/*.php' )
			);
		}
	}

	array_walk( $files, function( &$value, &$key, $base_directory) {
		$value = str_replace(
			[ $base_directory . '/', '.php', '-'],
			[ '', '', '_'],
			$value
		);
		$value = strtolower( $value );
	}, $base_directory );

	return $files;
}

/**
 * Geeft array met provincies van Nederland terug
 * 
 * @since     3.0.0
 *
 * @return array
 */
function siw_get_dutch_provinces() {
	$dutch_provinces = [
		'nb' => __( 'Brabant', 'siw' ),
		'dr' => __( 'Drenthe', 'siw' ),
		'fl' => __( 'Flevoland', 'siw' ),
		'fr' => __( 'Friesland', 'siw' ),
		'ge' => __( 'Gelderland', 'siw' ),
		'gr' => __( 'Groningen', 'siw' ),
		'li' => __( 'Limburg', 'siw' ),
		'nh' => __( 'Noord-Holland', 'siw' ),
		'ov' => __( 'Overijssel', 'siw' ),
		'ut' => __( 'Utrecht', 'siw' ),
		'ze' => __( 'Zeeland', 'siw' ),
		'zh' => __( 'Zuid-Holland', 'siw' ),
	];
	return $dutch_provinces;
}

/**
 * Geeft array met bestuursfuncties terug
 * 
 * @since     3.0.0
 * 
 * @return array
 */
function siw_get_board_titles() {
	$titles = [
		'chair'        => __( 'Voorzitter', 'siw' ),
		'secretary'    => __( 'Secretaris' , 'siw' ),
		'treasurer'    => __( 'Penningmeester' , 'siw' ),
		'board_member' => __( 'Algemeen bestuurslid' , 'siw' ),
	];
	return $titles;
}

/**
 * Geeft array met projectsoorten terug
 * 
 * @since     3.0.0
 * 
 * @return array
 * 
 * @todo moet hier altijd de duur/uitleg bij?
 */
function siw_get_project_types() {

	$project_types = [
		'groepsprojecten' => __( 'Groepsvrijwilligerswerk (2 - 3 weken)', 'siw' ),
		'op_maat'         => __( 'Vrijwilligerswerk Op Maat (3 weken tot een jaar)', 'siw' ),
		'esc'             => __( 'ESC (European Solidarity Corps)', 'siw' ),
	];
	return $project_types;
}

/**
 * Geeft een array met geslachten terug
 * 
 * @since     3.0.0
 *
 * @return array
 */
function siw_get_genders() {
	$genders = [
		'M' => __( 'Man', 'siw' ),
		'F' => __( 'Vrouw', 'siw' ),
	];
	return $genders;
}

/**
 * Geeft een array met nationaliteiten terug
 * 
 * @since     3.0.0
 *
 * @return array
 */
function siw_get_nationalities() {
	$nationalities = [ '' => __( 'Selecteer een nationaliteit', 'siw' ) ];
	$nationalities = $nationalities + siw_get_data( 'nationalities' );
	return $nationalities;
}

/**
 * Geeft array met dagen terug
 * 
 * Nummering volgens ISO-8601 (Maandag = 1, Zondag = 7)
 * @return array
 */
function siw_get_days() {
	$days = [
		1 => __( 'Maandag', 'siw' ),
		2 => __( 'Dinsdag', 'siw' ),
		3 => __( 'Woensdag', 'siw' ),
		4 => __( 'Donderdag', 'siw' ),
		5 => __( 'Vrijdag', 'siw' ),
		6 => __( 'Zaterdag', 'siw' ),
		7 => __( 'Zondag', 'siw' ),
	];
	return $days;
}

/**
 * Haalt email-instellingen op
 *
 * @param string $id
 * @return array
 * 
 * @todo fallback naar admin-email
 */
function siw_get_email_settings( string $id ) {
	$mail_settings = siw_get_option( "{$id}_email" );
	if ( ! isset( $mail_settings['use_specific'] ) || ! $mail_settings['use_specific'] ) {
		$mail_settings = siw_get_option( 'email_settings' );
	}
	return $mail_settings;
}
