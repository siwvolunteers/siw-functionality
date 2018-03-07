<?php
/*
 * (c)2018 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Genereert <ul> van array
 * @param  array $items
 * @return string
 */
function siw_generate_unordered_list( $items ) {
	if ( empty ( $items ) ) {
		return false;
	}

	$unordered_list = '<ul>';
	foreach ( $items as $item ) {
		$unordered_list .= '<li>' . (string) $item . '</li>';
	}
	$unordered_list .= '</ul>';

	return $unordered_list;
}

/**
 * Genereert externe link
 * @param  string $url
 * @param  string $text
 * @return string
 */
function siw_generate_external_link( $url, $text = false ) {

	if ( false == $text ) {
		$text = $url;
	}
	$external_link = sprintf( '<a class="siw-external-link" href="%s" target="_blank" rel="noopener">%s&nbsp;<i class="kt-icon-newtab"></i></a>', esc_url( $url ), esc_html( $text ) );

	return $external_link;
}


/**
 * Formatteert getal als bedrag
 * @param  float  $amount
 * @param  integer $decimals
 * @return string
 */
function siw_format_amount( $amount, $decimals = 0 ) {
	$amount = number_format( $amount, $decimals );
	return sprintf( '&euro; %s', $amount );
}


/**
 * Formatteert getal als percentage
 * @param  float  $percentage
 * @param  integer $decimals
 * @return string
 */
function siw_format_percentage( $percentage, $decimals = 0 ) {
	$percentage = number_format( $percentage, $decimals );
	return sprintf( '%s &percnt;', $percentage );
}


/**
 * Geneereer pinnacle accordion
 * @param  array $panes
 * @return string
 */
function siw_generate_accordion( $panes ) {

	if ( empty( $panes) ) {
		return;
	}
	$accordion = '[accordion]';
		foreach ( $panes as $pane ) {
		$accordion .= sprintf( '[pane title="%s"]%s[/pane]', esc_html( $pane['title'] ), wp_kses_post( wpautop( $pane['content'] ) ) );
	}
	$accordion .= '[/accordion]';

	return $accordion;
}

add_filter( 'siw_accordion', function( $accordion, $panes ) {
	return siw_generate_accordion( $panes );
}, 10, 2 );
