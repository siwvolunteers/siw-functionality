<?php
/**
 * SIW Functionaliteit
 *
 * @package     SIW
 * @author      Maarten Bruna
 * @copyright   2017-2019 SIW Internationale Vrijwilligersprojecten
 *
 * @wordpress-plugin
 * Plugin Name: SIW Functionaliteit
 * Plugin URI:  https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Version:     1.9.2
 * Author:      Maarten Bruna
 * Text Domain: siw
 */

/** Constantes */
define ( 'SIW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define ( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . '/assets' );
define ( 'SIW_VENDOR_DIR', SIW_ASSETS_DIR . '/vendor' );
define ( 'SIW_TEMPLATES_DIR', SIW_PLUGIN_DIR . '/templates' );
define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . '/includes' );
define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define ( 'SIW_ASSETS_URL', SIW_PLUGIN_URL . 'assets/' );
define ( 'SIW_VENDOR_URL', SIW_ASSETS_URL . 'vendor/' );
define ( 'SIW_PLUGIN_VERSION', '1.9.2' );
define ( 'SIW_SITE_URL', get_home_url() );
define ( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL )['host'] );

/* Hulp-plugins */
require_once( SIW_PLUGIN_DIR . '/vendor/autoload.php' );
require_once( SIW_VENDOR_DIR . '/rapid-addon.php' );

/* Basisfunctionaliteit: referentiegegevens, functies en instellingen */
require_once( SIW_INCLUDES_DIR . '/reference-data/init.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-formatting.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-properties.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-util.php' );


require_once( SIW_INCLUDES_DIR . '/settings/init.php' );

/* Core */
require_once( SIW_INCLUDES_DIR . '/class-siw-assets.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-head.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-htaccess.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-i18n.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-scheduler.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-shortcodes.php' );
require_once( SIW_INCLUDES_DIR . '/class-siw-widgets.php' );

add_action( 'plugins_loaded', ['SIW_Assets', 'init']);
add_action( 'plugins_loaded', ['SIW_i18n', 'init']);
add_action( 'plugins_loaded', ['SIW_Head', 'init']);
add_action( 'plugins_loaded', ['SIW_htaccess', 'init']);
add_action( 'plugins_loaded', ['SIW_Scheduler', 'init']);
add_action( 'plugins_loaded', ['SIW_Shortcodes', 'init']);
add_action( 'plugins_loaded', ['SIW_Widgets', 'init']);

require_once( SIW_INCLUDES_DIR . '/functions/init.php' );

/* Diverse aanpassingen */
require_once( SIW_INCLUDES_DIR . '/admin/admin.php' );
require_once( SIW_INCLUDES_DIR . '/api/api.php' );
require_once( SIW_INCLUDES_DIR . '/background-process/init.php' );
require_once( SIW_INCLUDES_DIR . '/compatibility/compatibility.php' );
require_once( SIW_INCLUDES_DIR . '/email/init.php');
require_once( SIW_INCLUDES_DIR . '/external/external.php' );
require_once( SIW_INCLUDES_DIR . '/forms/init.php' );
require_once( SIW_INCLUDES_DIR . '/maps/maps.php' );
require_once( SIW_INCLUDES_DIR . '/modules/modules.php' );
require_once( SIW_INCLUDES_DIR . '/plato-interface/plato-interface.php' );
require_once( SIW_INCLUDES_DIR . '/post-types/init.php' );
require_once( SIW_INCLUDES_DIR . '/woocommerce/init.php' );
