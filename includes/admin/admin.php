<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen voor admin
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */

require_once( __DIR__ . '/class-siw-admin.php' );
require_once( __DIR__ . '/class-siw-admin-bar.php' );
require_once( __DIR__ . '/class-siw-admin-notices.php' );
require_once( __DIR__ . '/class-siw-admin-shortcodes.php' );
require_once( __DIR__ . '/class-siw-admin-login.php' );
require_once( __DIR__ . '/class-siw-admin-properties-page.php' );

add_action( 'plugins_loaded', ['SIW_Admin', 'init']);
add_action( 'plugins_loaded', ['SIW_Admin_Bar', 'init']);
add_action( 'plugins_loaded', ['SIW_Admin_Notices', 'init']);
add_action( 'plugins_loaded', ['SIW_Admin_Shortcodes', 'init']);
add_action( 'plugins_loaded', ['SIW_Admin_Login', 'init']);
add_action( 'plugins_loaded', ['SIW_Admin_Properties_Page', 'init']);