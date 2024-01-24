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

/** Formatteert kortingsbedrag */
function siw_format_sale_amount( float $amount, float $sale_amount, int $decimals = 0, string $currency_code = 'EUR' ): string {
	return sprintf(
		'<del>%s</del>&nbsp;<ins>%s</ins>',
		siw_format_amount( $amount, $decimals, $currency_code ),
		siw_format_amount( $sale_amount, $decimals, $currency_code )
	);
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

/** Formatteert lokale bijdrage */
function siw_format_local_fee( float $fee, string $currency_code ): string {
	if ( 0.0 === $fee || empty( $currency_code ) ) {
		return '';
	}

	if ( 'EUR' === $currency_code ) {
		return sprintf( '&euro; %s', $fee );
	}

	$currency = Currency::tryFrom( $currency_code );
	if ( null !== $currency ) {
		return sprintf( '%s %d (%s)', $currency->symbol(), $fee, $currency->label() );
	}

	return sprintf( '%s %d', $currency_code, $fee );
}

/** Formatteert aantal vrijwilligers TODO: i18n */
function siw_format_number_of_volunteers( int $total, int $male, int $female ): string {
	$male_label = ( 1 === $male ) ? 'man' : 'mannen';
	$female_label = ( 1 === $female ) ? 'vrouw' : 'vrouwen';

	if ( ( $male + $female ) === $total ) {
		$number_of_volunteers = sprintf( '%d (%d %s en %d %s)', $total, $male, $male_label, $female, $female_label );
	} else {
		$number_of_volunteers = strval( $total );
	}
	return $number_of_volunteers;
}

/** Formatteert leeftijdsrange TODO: i18n */
function siw_format_age_range( int $min_age, int $max_age ): string {
	if ( $min_age < 1 ) {
		$min_age = 18;
	}
	if ( $max_age < 1 ) {
		$max_age = 99;
	}
	return sprintf( '%d t/m %d jaar', $min_age, $max_age );
}
