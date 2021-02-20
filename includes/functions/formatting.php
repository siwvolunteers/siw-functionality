<?php

/**
 * Functies t.b.v. formattering
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

/** Formatteert getal als percentage */
function siw_format_percentage( float $percentage, int $decimals = 0 ) : string {
	$percentage = number_format_i18n( $percentage, $decimals );
	return sprintf( '%s&nbsp;&percnt;', $percentage );
}

/** Formatteert getal als bedrag */
function siw_format_amount( float $amount, int $decimals = 0, string $currency_code = 'EUR' ) : string {
	$currency = siw_get_currency( $currency_code );
	$currency_symbol = $currency_code;
	if ( is_a( $currency, Currency::class ) ) {
		$currency_symbol = $currency->get_symbol();
	}
	$amount = number_format_i18n( $amount, $decimals );
	return sprintf( '%s&nbsp;%s', $currency_symbol, $amount );
}

/** Formatteert kortingsbedrag */
function siw_format_sale_amount( float $amount, float $sale_amount, int $decimals = 0, string $currency_code = 'EUR' ) : string {
	return sprintf(
		'<del>%s</del>&nbsp;<ins>%s</ins>',
		siw_format_amount( $amount, $decimals, $currency_code ),
		siw_format_amount( $sale_amount, $decimals, $currency_code )
	);
}

/** Formatteert datum als tekst */
function siw_format_date( string $date, bool $include_year = true ) : string {
	$format = $include_year ? 'j F Y' : 'j F';
	return wp_date( $format, strtotime( $date ) ); 
}


/** Formatteert datumrange als tekst TODO: checkdate of DateTime object*/
function siw_format_date_range( string $date_start, string $date_end, bool $include_year = true ) : string {
	
	if ( $date_start === $date_end ) {
		return siw_format_date( $date_start, $include_year );
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
function siw_format_month( string $date, bool $include_year = true ) : string {
	$format = $include_year ? 'F Y' :  'F';
	return wp_date( $format, strtotime( $date ) );
}
