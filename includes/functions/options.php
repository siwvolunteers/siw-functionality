<?php declare(strict_types=1);

use Pharaonic\DotArray\DotArray;

/** Haal optie op */
function siw_get_option( string $option, $default_value = null ) {

	// Foutmelding bij aanroepen vóór init
	if ( 0 === did_action( 'init' ) && WP_DEBUG ) {
		wp_trigger_error( __FUNCTION__, 'Deze function werd te vroeg aangeroepen', E_USER_ERROR );
	}

	// Probeer waarde uit cache te halen
	$value = wp_cache_get( $option, __FUNCTION__ );
	if ( false !== $value ) {
		return $value;
	}
	$options = get_option( SIW_OPTIONS_KEY );

	$dot = new DotArray( $options );
	$value = $dot->get( $option );

	if ( empty( $value ) ) {
		return $default_value;
	}

	wp_cache_set( $option, $value, __FUNCTION__ );

	return $value;
}
