<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Opties
 * 
 * @package   SIW\Options
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
require_once( __DIR__ . '/data-dutch-projects.php' );

require_once( __DIR__ . '/class-siw-options-page.php' );
add_action( 'plugins_loaded', [ 'SIW_Options_Page', 'init' ] );

/**
 * Haal optie op
 *
 * @param string $option
 * @param mixed $default
 * @return mixed
 * 
 * @todo constante voor optienaam
 */
function siw_get_option( $option, $default = null ) {

	$value = rwmb_meta( $option, [ 'object_type' => 'setting' ], 'siw_options' );

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

	}

	return $value;
}
