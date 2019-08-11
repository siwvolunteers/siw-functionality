<?php
/**
 * Functies m.b.t. referentiegegevens
 * 
 * @package   SIW\Functions
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

/**
 * Haalt data uit bestand
 *
 * @param string $file
 * @param string $dir
 * 
 * @return mixed
 */
function siw_get_data( string $file, string $dir = null ) {
	$file = strtolower( str_replace( '_', '-', $file ) );

	$data_file = SIW_DATA_DIR . '/';
	if ( null !== $dir ) {
		$data_file .= "{$dir}/";
	}
	$data_file .= "{$file}.php";

	if ( ! file_exists( $data_file ) ) {
		return null;
	}
	$data = require $data_file;
	return $data;
}

/**
 * Geeft data-file id's uit specifieke directory terug
 *
 * @param string $subdir
 * @return array
 */
function siw_get_data_file_ids( string $subdir ) {

	$dir = SIW_DATA_DIR . "/{$subdir}/";
	$files = glob( $dir . '*.php' );

	array_walk( $files, function( &$value, &$key, $dir) {
		$value = str_replace(
			[ $dir, '.php', '-'],
			[ '', '', '_'],
			$value
		);
		$value = strtolower( $value );
	}, $dir );

	return $files;
}

/**
 * Geeft array met provincies van Nederland terug
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
 * @return array
 */
function siw_get_nationalities() {
	$nationalities = [ '' => __( 'Selecteer een nationaliteit', 'siw' ) ];
	$nationalities = $nationalities + siw_get_data( 'nationalities' );
	return $nationalities;
}
