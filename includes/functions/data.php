<?php declare(strict_types=1);

use SIW\Data\Email_Settings;
use SIW\Integrations\Google_Maps;

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
function siw_get_dutch_provinces(): array {
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
function siw_get_dutch_province( string $slug ): ?string {
	$provinces = siw_get_dutch_provinces();
	return $provinces[ $slug ] ?? null;
}

/** Geeft lijst met bestuursfuncties terug */
function siw_get_board_titles(): array {
	$titles = [
		'chair'        => __( 'Voorzitter', 'siw' ),
		'secretary'    => __( 'Secretaris', 'siw' ),
		'treasurer'    => __( 'Penningmeester', 'siw' ),
		'board_member' => __( 'Algemeen bestuurslid', 'siw' ),
	];
	return $titles;
}

/** Geeft bestuursfunctie terug obv slug */
function siw_get_board_title( string $slug ): ?string {
	$board_titles = siw_get_board_titles();
	return $board_titles[ $slug ] ?? null;
}

/**
 * Geeft lijst met projectsoorten terug
 *
 * @todo moet hier altijd de duur/uitleg bij?
 */
function siw_get_project_types(): array {

	$project_types = [
		'groepsprojecten' => __( 'Groepsvrijwilligerswerk (2 - 3 weken)', 'siw' ),
		'op_maat'         => __( 'Vrijwilligerswerk Op Maat (3 weken tot een jaar)', 'siw' ),
		'esc'             => __( 'ESC (European Solidarity Corps)', 'siw' ),
		'scholenproject'  => __( 'Scholenproject (internationale stage of tussenjaar)', 'siw' ),
	];
	return $project_types;
}

/** Geeft een lijst met geslachten terug */
function siw_get_genders(): array {
	$genders = [
		'M' => __( 'Man', 'siw' ),
		'F' => __( 'Vrouw', 'siw' ),
	];
	return $genders;
}

/** Geeft een lijst met nationaliteiten terug */
function siw_get_nationalities(): array {
	$nationalities = siw_get_data( 'nationalities' );
	asort( $nationalities );
	$nationalities = [ '' => __( 'Selecteer een nationaliteit', 'siw' ) ] + $nationalities;
	return $nationalities;
}

/**
 * Haalt email-instellingen op
 *
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
function siw_get_interactive_maps(): array {
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
function siw_get_opening_hours(): array {
	global $wp_locale;

	$opening_periods = get_transient( __FUNCTION__ );
	if ( ! is_array( $opening_periods ) ) {
		$place_details = Google_Maps::create()->get_place_details();
		$opening_periods = $place_details['current_opening_hours']['periods'] ?? [];
		if ( ! empty( $opening_periods ) ) {
			set_transient( __FUNCTION__, $opening_periods, DAY_IN_SECONDS );
		}
	}

	$opening_hours = [];
	foreach ( $opening_periods as $period ) {
		$day = $period['open']['day'];
		$open = wp_date( __( 'g:i a' ), strtotime( $period['open']['time'] . wp_timezone_string() ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		$close = wp_date( __( 'g:i a' ), strtotime( $period['close']['time'] . wp_timezone_string() ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		$opening_hours[ $day ][] = "{$open}-{$close}";
	}

	$daterange = new \DatePeriod( new \DateTime( 'today' ), new \DateInterval( 'P1D' ), 6 );
	foreach ( $daterange as $date ) {
		$day_number = (int) $date->format( 'w' );
		$day_name = ucfirst( $wp_locale->get_weekday( $day_number ) );
		$opening_times = isset( $opening_hours[ $day_number ] ) ? implode( ',', $opening_hours[ $day_number ] ) : __( 'gesloten', 'siw' );

		// Huidige dag bold maken
		$data[] = [
			( $daterange->start === $date ) ? '<b>' . $day_name . '</b>' : $day_name,
			( $daterange->start === $date ) ? '<b>' . $opening_times . '</b>' : $opening_times,
		];

	}
	return $data;
}
