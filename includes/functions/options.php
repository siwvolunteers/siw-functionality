<?php

/**
 * Functies m.b.t. opties
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */

use SIW\Core\Options;

/**
 * Haal optie op
 * 
 * @since     3.0.0
 *
 * @param string $option
 * @param mixed $default
 * @return mixed
 */
function siw_get_option( $option, $default = null ) {

	//Foutmelding bij aanroepen vóór init
	if ( 0 === did_action( 'init' ) && WP_DEBUG ) {
		trigger_error( 'siw_get_option werd te vroeg aangeroepen', E_USER_ERROR );
	}

	//Probeer waarde uit cache te halen
	$value = wp_cache_get( $option, 'siw_options');
	if ( false !== $value ) {
		return $value;
	}

	$options = get_option( Options::OPTION_NAME );
	$value = $options[ $option ] ?? null;

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

/**
 * Werk optie bij
 *
 * @param string $option
 * @param mixed $value
 */
function siw_set_option( string $option, $value ) {
	$options = get_option( Options::OPTION_NAME );
	if ( null === $value ) {
		unset( $options[ $option ] );
	}
	else {
		$options[ $option ] = $value;
	}
	update_option( Options::OPTION_NAME, $options );
}
