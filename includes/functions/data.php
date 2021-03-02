<?php declare(strict_types=1);

use Adbar\Dot;

/**
 * Functies m.b.t. referentiegegevens
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */

/** Haalt data uit bestand */
function siw_get_data( string $file ) {
	$file = strtolower( str_replace( '_', '-', $file ) );

	$data_file = SIW_DATA_DIR . "{$file}.php";

	if ( ! file_exists( $data_file ) ) {
		return null;
	}
	$data = require $data_file;
	return $data;
}

/** Wrapper om rwmb_meta */
function siw_meta( string $key, array $args = [], int $post_id = null ) {
	if ( function_exists( 'rwmb_meta' ) ) {
		$keys = explode( '.', $key );
		$value = rwmb_meta( $keys[0], $args, $post_id );

		unset( $keys[0]);
		if ( ! empty( $keys ) ) {
			$dot = new Dot( $value );
			$key = implode( '.', $keys );
			$value = $dot->get( $key );
		}

		return $value;
	}
	return null;
}

/** Geeft data-file id's uit specifieke directory terug */
function siw_get_data_file_ids( string $directory, bool $include_subdirectories = true ) : array {

	$base_directory = SIW_DATA_DIR . $directory;
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

/** Geeft lijst met provincies van Nederland terug */
function siw_get_dutch_provinces() : array {
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

/** Geeft naam van provincie van Nederland terug o.b.v. slug */
function siw_get_dutch_province( string $slug ) : ?string {
	$provinces = siw_get_dutch_provinces();
	return $provinces[ $slug ] ?? null;
}

/** Geeft lijst met bestuursfuncties terug */
function siw_get_board_titles() : array {
	$titles = [
		'chair'        => __( 'Voorzitter', 'siw' ),
		'secretary'    => __( 'Secretaris' , 'siw' ),
		'treasurer'    => __( 'Penningmeester' , 'siw' ),
		'board_member' => __( 'Algemeen bestuurslid' , 'siw' ),
	];
	return $titles;
}

/** Geeft bestuursfunctie terug obv slug */
function siw_get_board_title( string $slug ) : ?string {
	$board_titles = siw_get_board_titles();
	return $board_titles[ $slug ] ?? null;
}

/**
 * Geeft lijst met projectsoorten terug
 * @todo moet hier altijd de duur/uitleg bij?
 */
function siw_get_project_types() : array {

	$project_types = [
		'groepsprojecten' => __( 'Groepsvrijwilligerswerk (2 - 3 weken)', 'siw' ),
		'op_maat'         => __( 'Vrijwilligerswerk Op Maat (3 weken tot een jaar)', 'siw' ),
		'esc'             => __( 'ESC (European Solidarity Corps)', 'siw' ),
		'scholenproject'  => __( 'Scholenproject (internationale stage of tussenjaar)', 'siw' ),
	];
	return $project_types;
}

/** Geeft een lijst met geslachten terug */
function siw_get_genders() : array {
	$genders = [
		'M' => __( 'Man', 'siw' ),
		'F' => __( 'Vrouw', 'siw' ),
	];
	return $genders;
}

/** Geeft een lijst met nationaliteiten terug */
function siw_get_nationalities() : array {
	$nationalities = [ '' => __( 'Selecteer een nationaliteit', 'siw' ) ];
	$nationalities = $nationalities + siw_get_data( 'nationalities' );
	return $nationalities;
}

/**
 * Geeft array met dagen terug
 * 
 * Nummering volgens ISO-8601 (Maandag = 1, Zondag = 7)
 */
function siw_get_days() : array {
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
 * @todo fallback naar admin-email
 */
function siw_get_email_settings( string $id ) : array {
	$mail_settings = siw_get_option( "{$id}_email" );
	if ( ! isset( $mail_settings['use_specific'] ) || ! $mail_settings['use_specific'] ) {
		$mail_settings = siw_get_option( 'email_settings' );
	}
	return $mail_settings;
}

/** Geeft lijst met formulieren terug */
function siw_get_forms() : array {
	if ( ! class_exists( \Caldera_Forms_Forms::class ) ) {
		return [];
	}
	return wp_list_pluck( Caldera_Forms_Forms::get_forms( true, false ), 'name' );
}
