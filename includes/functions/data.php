<?php declare(strict_types=1);

use SIW\Data\Email_Settings;

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
 * Haalt email-instellingen op
 * @todo fallback naar admin-email
 */
function siw_get_email_settings( string $id ): Email_Settings {
	$mail_settings = siw_get_option( "email_settings.{$id}" );
	if ( ! isset( $mail_settings['use_specific'] ) || ! $mail_settings['use_specific'] ) {
		$mail_settings = siw_get_option( 'email_settings.default' );
	}
	return new Email_Settings( $mail_settings );
}

/** Geeft lijst met formulieren terug */
function siw_get_forms(): array {
	$forms = apply_filters( 'siw_forms', [] );
	asort( $forms );

	return $forms;
}

/** Haalt gegevens over interactieve kaarten op */
function siw_get_interactive_maps() : array {
	$maps = [
		[
			'id'    => 'nl',
			'name'  => __( 'Nederland', 'siw' ),
			'class' => \SIW\Elements\Interactive_Maps\Netherlands::class,
		],
		[
			'id'    => 'destinations',
			'name'  => __( 'Bestemmingen', 'siw' ),
			'class' => \SIW\Elements\Interactive_Maps\Destinations::class,
		],
		[
			'id'    => 'esc',
			'name'  => __( 'ESC', 'siw' ),
			'class' => \SIW\Elements\Interactive_Maps\ESC::class,
		],
	];
	return $maps;
}

/** Geeft openingstijden van SIW terug */
function siw_get_opening_hours() : array {
	global $wp_locale;

	//Ophalen openingstijden
	$opening_hours = siw_get_option( 'opening_hours' );

	$opening_hours = array_map(
		fn( array $value ) : string => $value['open'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' ),
		array_filter( $opening_hours )
	);

	//Ophalen afwijkende openingstijden
	$special_opening_hours = siw_get_option( 'special_opening_hours', [] );

	$special_opening_hours = array_map(
		fn( array $value ) : string => $value['opened'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' ),
		array_column( $special_opening_hours , null, 'date' )
	);

	$daterange = new \DatePeriod( new \DateTime( 'today' ), new \DateInterval( 'P1D' ), 6 );
	foreach ( $daterange as $date ) {
		$day_number = $date->format( 'w' );
		$day_name = ucfirst( $wp_locale->get_weekday( $day_number ) );
		$opening_times = $opening_hours[ "day_{$day_number}" ];

		
		// Bepaal afwijkende openingstijden (indien van toepassing)
		if ( isset( $special_opening_hours[ $date->format( 'Y-m-d' ) ] ) ) {
			$opening_times = sprintf( '<del>%s</del> <ins>%s</ins>', $opening_times, $special_opening_hours[ $date->format( 'Y-m-d' ) ] );
		}
		//Huidige dag bold maken 
		$data[] = [
			( $daterange->start == $date ) ? '<b>' . $day_name . '</b>' : $day_name,
			( $daterange->start == $date ) ? '<b>' . $opening_times . '</b>' : $opening_times,
		];
		
	}
	return $data;
}