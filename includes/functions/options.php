<?php

/**
 * Functies m.b.t. opties
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

use SIW\i18n;
use SIW\Options;

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

	//TODO: omgaan met array als $option i.p.v. string
	$options = get_option( Options::OPTION_NAME );
	$value = $options[ $option ] ?? null;

	if ( empty( $value ) ) {
		return $default;
	}

	//TODO: Verplaatsen naar SIW_Options en splitsen
	switch ( $option ) {
		case 'dutch_projects':
			$language = i18n::get_current_language();
			$defaults = [
				'code'                    => '',
				"name_{$language}"        => '',
				'city'                    => '',
				'province'                => '',
				'latitude'                => null,
				'longitude'               => null,
				'start_date'              => [ 'timestamp' => 0 ],
				'end_date'                => [ 'timestamp' => 0 ],
				'work_type'               => '',
				'participants'            => 0,
				'local_fee'               => null,
				"description_{$language}" => '',
				'image'                   => null,
			];

			$callback = function( &$value, $key ) use ( $defaults ) {
				$value = wp_parse_args( $value, $defaults );
			};
			array_walk( $value, $callback, $defaults );
			break;
		
		case 'board_members':
			$titles = siw_get_board_titles();
			$callback = function( &$value, $key ) use ( $titles ) {
				$value['title'] = ( isset( $value['title'] ) && isset( $titles[ $value['title'] ] ) ) ? $titles[ $value['title'] ] : '';
			};
			array_walk( $value, $callback, $titles );
			break;
		case 'info_days':
		case 'esc_deadlines':
			$value = array_filter( $value, function( $date ) {
				return $date >= date( 'Y-m-d' );
			});
			sort( $value );
			break;
		case 'special_opening_hours':
			$value = array_column( $value , null, 'date' );
			$callback = function( &$value, $key ) {
				$value = $value['opened'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' );
			};
			array_walk( $value, $callback );
			break;
		case 'opening_hours':
			$callback = function( &$value, $key ) {
				$value = $value['open'] ? sprintf( '%s-%s', $value['opening_time'], $value['closing_time'] ) : __( 'gesloten', 'siw' );
			};
			array_walk( $value, $callback );
	}

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
