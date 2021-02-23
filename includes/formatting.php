<?php declare(strict_types=1);

namespace SIW;

use SIW\Data\Currency;

/**
 * Hulpfuncties t.b.v. formattering
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Formatting {

	/** Parset template o.b.v. variabelen */
	public static function parse_template( string $template, array $vars ) : string {
		$variables = [];
		foreach ( $vars as $key => $value ) {
			$variables[ '{{ ' . $key . ' }}' ] = $value;
		}
		return strtr( $template, $variables  );
	}

	/** Formatteert maand uit datum als tekst */
	public static function format_month( string $date, bool $include_year = true ) : string {
		$format = $include_year ? 'F Y' :  'F';
		return wp_date( $format, strtotime( $date ) );
	}

	/** Formatteert lokale bijdrage */
	public static function format_local_fee( float $fee, string $currency_code ) : string {
		if ( 0.0 === $fee || ! is_string( $currency_code ) ) {
			return '';
		}

		if ( 'EUR' == $currency_code ) {
			return sprintf( '&euro; %s', $fee );
		}

		$currency = siw_get_currency( $currency_code );
		if ( is_a( $currency, Currency::class ) ) {
			return sprintf( '%s %d (%s)', $currency->get_symbol(), $fee, $currency->get_name() );
		}
		
		return sprintf( '%s %d', $currency_code, $fee );
	}

	/** Formatteert aantal vrijwilligers */
	public static function format_number_of_volunteers( int $total, int $male, int $female ) : string {

		$male_label = ( 1 == $male ) ? 'man' : 'mannen';
		$female_label = ( 1 == $female ) ? 'vrouw' : 'vrouwen';
	
		if ( $total == ( $male + $female ) ) {
			$number_of_volunteers = sprintf( '%d (%d %s en %d %s)', $total, $male, $male_label, $female, $female_label );
		}
		else {
			$number_of_volunteers = strval( $total );
		}
		return $number_of_volunteers;
	}

	/** Formatteert leeftijdsrange */
	public static function format_age_range( int $min_age, int $max_age ) : string {
		if ( $min_age < 1 ) {
			$min_age = 18;
		}
		if ( $max_age < 1 ) {
			$max_age = 99;
		}
		return sprintf( '%d t/m %d jaar', $min_age, $max_age );
	}
}
