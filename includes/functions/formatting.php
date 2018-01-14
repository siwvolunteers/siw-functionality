<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * [siw_generate_unordered_list description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function siw_generate_unordered_list( $data ) {
	if ( empty ( $data ) ) {
		return false;
	}

	$unordered_list = '<ul>';
	foreach ( $data as $item ) {
		$unordered_list .= '<li>' . (string) $item . '</li>';
	}
	$unordered_list .= '</ul>';

	return $unordered_list;
}

/**
 * [siw_generate_external_link description]
 * @param  string $url  [description]
 * @param  string $text [description]
 * @return string       [description]
 */
function siw_generate_external_link( $url, $text = false ) {

	if ( false == $text ) {
		$text = $url;
	}
	$external_link = sprintf( '<a href="%s" target="_blank" rel="noopener">%s&nbsp;<i class="kt-icon-newtab"></i></a>', esc_url( $url ), esc_html( $text ) );

	return $external_link;
}

/**
 * [siw_format_amount description]
 * @param  float  $amount
 * @param  integer $decimals
 * @return string
 */
function siw_format_amount( $amount, $decimals=0 ) {
	$amount = number_format( $amount, $decimals );
	return sprintf( '&euro; %s', $amount );
}


/**
 * [siw_format_percentage description]
 * @param  float  $percentage
 * @param  integer $decimals
 * @return string
 */
function siw_format_percentage( $percentage, $decimals=0 ) {
	$percentage = number_format( $percentage, $decimals );
	return sprintf( '%s &percnt;', $percentage );
}
