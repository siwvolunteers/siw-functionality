<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Modules
 * 
 * @package SIW\Modules
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo switches voor modules in optiemenu
 */

require_once( __DIR__ . '/class-siw-analytics.php' );
require_once( __DIR__ . '/class-siw-cookie-notification.php' );
require_once( __DIR__ . '/class-siw-social-share.php' );
require_once( __DIR__ . '/class-siw-topbar.php' );


add_action( 'init', [ 'SIW_Cookie_Notification', 'init' ] );
add_action( 'init', [ 'SIW_Topbar', 'init' ] );
add_action( 'init', [ 'SIW_Analytics', 'init' ] );
add_action( 'init', [ 'SIW_Social_Share', 'init' ] );
