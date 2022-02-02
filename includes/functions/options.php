<?php declare(strict_types=1);

use Pharaonic\DotArray\DotArray;

/**
 * Functies m.b.t. opties
 * 
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */

/** Haal optie op */
function siw_get_option( string $option, $default = null ) {

	//Foutmelding bij aanroepen vóór init
	if ( 0 === did_action( 'init' ) && WP_DEBUG ) {
		trigger_error( 'siw_get_option werd te vroeg aangeroepen', E_USER_ERROR );
	}

	//Probeer waarde uit cache te halen
	$value = wp_cache_get( $option, 'siw_options');
	if ( false !== $value ) {
		return $value;
	}
	$options = get_option( 'siw_options' );

	$dot = new DotArray( $options );
	$value = $dot->get( $option );

	if ( empty( $value ) ) {
		return $default;
	}

	/**
	 * Waarde van optie
	 * 
	 * @param mixed $value
	 * @param string $option
	 */
	$value = apply_filters( 'siw_option_value', $value, $option );

	wp_cache_set( $option, $value, 'siw_options' );

	return $value;
}
