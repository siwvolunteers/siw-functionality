<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Modules
 * 
 * @package SIW\Modules
 * @author Maarten Bruna
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo class voor laden modules
 */

require_once( __DIR__ . '/class-siw-cache-refresh.php' );
require_once( __DIR__ . '/class-siw-google-analytics.php' );
require_once( __DIR__ . '/class-siw-cookie-notification.php' );
require_once( __DIR__ . '/class-siw-social-share.php' );
require_once( __DIR__ . '/class-siw-topbar.php' );

add_action( 'init', [ 'SIW_Cache_Refresh', 'init' ] );
add_action( 'init', [ 'SIW_Cookie_Notification', 'init' ] );
add_action( 'init', [ 'SIW_Topbar', 'init' ] );
add_action( 'init', [ 'SIW_Google_Analytics', 'init' ] );
add_action( 'init', [ 'SIW_Social_Share', 'init' ] );
