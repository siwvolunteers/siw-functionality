<?php
/**
 * Functies m.b.t. opties
 * 
 * @author    Maarten Bruna
 * @package   SIW\Functions
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */


/**
 * Haal optie op
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

	$options = get_option( SIW_Options::OPTION_NAME );
	$value = $options[ $option ] ?? null;

	if ( empty( $value ) ) {
		return $default;
	}

	//TODO: Verplaatsen naar SIW_Options en splitsen
	switch ( $option ) {
		case 'dutch_projects':
			$language = SIW_i18n::get_current_language();
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

	}

	wp_cache_set( $option, $value, 'siw_options' );

	return $value;
}
