<?php declare(strict_types=1);

use SIW\Data\Currency;

/** Formatteert getal als percentage */
function siw_format_percentage( float $percentage, int $decimals = 0 ): string {
	$percentage = number_format_i18n( $percentage, $decimals );
	return sprintf( '%s&nbsp;&#37;', $percentage );
}

/** Formatteert getal als bedrag */
function siw_format_amount( float $amount, int $decimals = 0, string $currency_code = Currency::EUR->value ): string {
	$currency = Currency::tryFrom( $currency_code );
	$currency_symbol = $currency_code;
	if ( null !== $currency ) {
		$currency_symbol = $currency->symbol();
	}
	$amount = number_format_i18n( $amount, $decimals );
	return sprintf( '%s&nbsp;%s', $currency_symbol, $amount );
}

/** Formatteert datum als tekst */
function siw_format_date( string $date, bool $include_year = true ): string {
	$format = $include_year ? 'j F Y' : 'j F';
	return wp_date( $format, strtotime( $date ) );
}


/** Formatteert datumrange als tekst TODO: checkdate of DateTime object*/
function siw_format_date_range( string $date_start, string $date_end, bool $include_year = true ): string {

	if ( $date_start === $date_end ) {
		return siw_format_date( $date_start, $include_year );
	}

	$date_start_array = date_parse( $date_start );
	$date_end_array = date_parse( $date_end );

	$format_end = $include_year ? 'j F Y' : 'j F';
	if ( $include_year && ( $date_start_array['year'] !== $date_end_array['year'] ) ) {
		$format_start = 'j F Y';
	} elseif ( $date_start_array['month'] !== $date_end_array['month'] ) {
		$format_start = 'j F';
	} else {
		$format_start = 'j';
	}

	return sprintf(
		// translators: %1$s is de startdatum, %2$s is de einddatum
		__( '%1$s t/m %2$s', 'siw' ),
		wp_date( $format_start, strtotime( $date_start ) ),
		wp_date( $format_end, strtotime( $date_end ) )
	);
}

/** Formatteert maand uit datum als tekst */
function siw_format_month( string $date, bool $include_year = true ): string {
	$format = $include_year ? 'F Y' : 'F';
	return wp_date( $format, strtotime( $date ) );
}
