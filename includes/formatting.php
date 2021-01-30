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

	/** Formatteert getal als percentage */
	public static function format_percentage( float $percentage, int $decimals = 0 ) {
		$percentage = number_format_i18n( $percentage, $decimals );
		return sprintf( '%s&nbsp;&percnt;', $percentage );
	}

	/** Formatteert getal als bedrag */
	public static function format_amount( float $amount, int $decimals = 0, string $currency_code = 'EUR' ) : string {
		$currency = siw_get_currency( $currency_code );

		$currency_symbol = $currency_code;
		if ( is_a( $currency, Currency::class ) ) {
			$currency_symbol = $currency->get_symbol();
		}
		
		$amount = number_format_i18n( $amount, $decimals );
		return sprintf( '%s&nbsp;%s', $currency_symbol, $amount );
	}

	/** Formatteert kortingsbedrag */
	public static function format_sale_amount( float $amount, float $sale_amount, int $decimals = 0, string $currency_code = 'EUR' ) : string {
		return sprintf(
			'<del>%s</del>&nbsp;<ins>%s</ins>',
			self::format_amount( $amount, $decimals, $currency_code ),
			self::format_amount( $sale_amount, $decimals, $currency_code )
		);
	}

	/** Parset template o.b.v. variabelen */
	public static function parse_template( string $template, array $vars ) : string {
		$variables = [];
		foreach ( $vars as $key => $value ) {
			$variables[ '{{ ' . $key . ' }}' ] = $value;
		}
		return strtr( $template, $variables  );
	}

	/** Formatteert datum als tekst */
	public static function format_date( $date, bool $include_year = true ) : string {
		$format = $include_year ? 'j F Y' : 'j F';
		return wp_date( $format, strtotime( $date ) );
	}

	/** Formatteert datumrange als tekst */
	public static function format_date_range( string $date_start, string $date_end, bool $include_year = true ) : string {
		
		if ( $date_start === $date_end ) {
			return self::format_date( $date_start, $include_year );
		}

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		$format_end = $include_year ? 'j F Y' :  'j F';
		if ( $include_year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'j F Y';
		}
		elseif ( $date_start_array['month'] != $date_end_array['month'] ) {
			$format_start = 'j F';
		}
		else {
			$format_start = 'j';
		}

		return sprintf(
			__( '%s t/m %s', 'siw' ),
			wp_date( $format_start, strtotime( $date_start ) ),
			wp_date( $format_end, strtotime( $date_end ) )
		);
	}

	/** Formatteert maand uit datum als tekst */
	public static function format_month( string $date, bool $include_year = true ) : string {
		$format = $include_year ? 'F Y' :  'F';
		return wp_date( $format, strtotime( $date ) );
	}

	/** Formatteert maand-range uit datums als tekst */
	public static function format_month_range( string $date_start, string $date_end, bool $include_year = true ) : string {

		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );

		if ( $date_start === $date_end || ( $date_start_array['month'] === $date_end_array['month'] && $date_start_array['year'] === $date_end_array['year'] ) ) {
			return self::format_month( $date_start, $include_year );
		}

		$format_end = $include_year ? 'F Y' :  'F';
		if ( $include_year && ( $date_start_array['year'] != $date_end_array['year'] ) ) {
			$format_start = 'F Y';
		}
		else {
			$format_start = 'F';
		}

		return sprintf(
			__( '%s t/m %s', 'siw' ),
			wp_date( $format_start, strtotime( $date_start ) ),
			wp_date( $format_end, strtotime( $date_end ) )
		);
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
