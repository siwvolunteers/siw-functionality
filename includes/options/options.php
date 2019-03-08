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
 */
function siw_get_option( $option, $default = null ) {

	$value = rwmb_meta( $option, [ 'object_type' => 'setting' ], 'siw_options' ); //TODO: constant voor optienaam
	return $value;
}
