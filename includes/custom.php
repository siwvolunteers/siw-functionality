<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Klaarzetten acties voor verwerken plugin-update */
add_action( 'wppusher_plugin_was_updated', function() {
	wp_schedule_single_event( time(), 'siw_update_plugin' );
});

/* PHP sessie-cookie httponly en secure maken*/
@ini_set( 'session.cookie_httponly', 'on' );
@ini_set( 'session.cookie_secure', 'on' );



/**
 * Schrijf informatie naar PHP-log
 *
 * @param mixed $content
 * @deprecated
 * @return void
 */
function siw_log( $content ) {
	_deprecated_function( __FUNCTION__ );
	error_log( print_r( $content, true ), 0);
}

/**
 *  Schrijf informatie naar log als DEBUG-mode aan staat
 * @param  mixed $content
 * @deprecated
 * @return void
 */
function siw_debug_log( $content ) {
	if ( WP_DEBUG ) {
		_deprecated_function( __FUNCTION__ );
		siw_log( $content );
	}
}


/* Query vars registeren voor Snel Zoeken */
add_filter( 'query_vars', function( $vars ) {
	$vars[] = 'bestemming';
	$vars[] = 'maand';
	return $vars;
} );




