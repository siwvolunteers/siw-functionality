<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( __DIR__ . '/class-siw-email-configuration.php' );
add_action( 'plugins_loaded', [ 'SIW_Email_Configuration', 'init' ] );

require_once( __DIR__ . '/template.php' );
