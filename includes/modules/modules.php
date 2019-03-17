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

require_once( __DIR__ . '/class-siw-module-cache-rebuild.php' );
require_once( __DIR__ . '/class-siw-module-cookie-notification.php' );
require_once( __DIR__ . '/class-siw-module-google-analytics.php' );
require_once( __DIR__ . '/class-siw-module-social-share.php' );
require_once( __DIR__ . '/class-siw-module-topbar.php' );

add_action( 'init', [ 'SIW_Module_Cache_Rebuild', 'init' ] );
add_action( 'init', [ 'SIW_Module_Cookie_Notification', 'init' ] );
add_action( 'init', [ 'SIW_Module_Google_Analytics', 'init' ] );
add_action( 'init', [ 'SIW_Module_Social_Share', 'init' ] );
add_action( 'init', [ 'SIW_Module_Topbar', 'init' ] );
