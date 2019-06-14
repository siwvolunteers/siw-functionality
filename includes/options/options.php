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
require_once( __DIR__ . '/data-configuration.php' );
require_once( __DIR__ . '/data-countries.php' );
require_once( __DIR__ . '/data-organization.php' );
require_once( __DIR__ . '/data-dates.php' );
require_once( __DIR__ . '/data-emails.php' );
require_once( __DIR__ . '/data-job-postings.php' );
require_once( __DIR__ . '/data-tailor-made.php' );
require_once( __DIR__ . '/data-topbar.php' );
require_once( __DIR__ . '/data-workcamps.php' );
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

	if ( empty( $value ) ) {
		return $default;
	}

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

	return $value;
}
