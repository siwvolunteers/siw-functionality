<?php
/*
 * Plugin Name: SIW Functionaliteit
 * Plugin URI: https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Author: Maarten Bruna
 * Version: 1.8.2
 */


/*
 * Definieer constantes m.b.t. plugin
 * - Directory
 * - URL
 * - Versie
 * - URL AJAX-handler
 */
define ( 'SIW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define ( 'SIW_ASSETS_DIR', SIW_PLUGIN_DIR . '/assets' );
define ( 'SIW_VENDOR_DIR', SIW_ASSETS_DIR . '/vendor' );
define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . '/includes' );
define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define ( 'SIW_ASSETS_URL', SIW_PLUGIN_URL . 'assets/' );
define ( 'SIW_VENDOR_URL', SIW_ASSETS_URL . '/vendor' );
define ( 'SIW_PLUGIN_VERSION', '1.8.2' );
define ( 'SIW_AJAX_URL', SIW_PLUGIN_URL . 'ajax-handler.php' );
define ( 'SIW_SITE_URL', get_home_url() );
define ( 'SIW_SITE_NAME', wp_parse_url( SIW_SITE_URL )['host'] );

/*
 * Hulp-plugins
 * - WP MultiFilter (https://github.com/khromov/wp-multifilter)
 * - Rapid Add-On (https://github.com/soflyy/wp-all-import-rapid-addon)
 * - WordPress Widgets Helper Class (https://github.com/alessandrotesoro/wp-widgets-helper)
 * - WP Background Processing (https://github.com/A5hleyRich/wp-background-processing)
 * - wp_parse_args_recursive (https://github.com/kallookoo/wp_parse_args_recursive)
 */
require_once( SIW_VENDOR_DIR . '/wp-multifilter.php' );
require_once( SIW_VENDOR_DIR . '/rapid-addon.php' );
require_once( SIW_VENDOR_DIR . '/wp-widgets-helper.php' );
require_once( SIW_VENDOR_DIR . '/wp-async-request.php' );
require_once( SIW_VENDOR_DIR . '/wp-background-process.php' );
require_once( SIW_VENDOR_DIR . '/wp-parse-args-recursive.php' );

/* Basisfunctionaliteit: referentiegegevens, functies en instellingen */
require_once( SIW_INCLUDES_DIR . '/reference-data/init.php' );
require_once( SIW_INCLUDES_DIR . '/functions/init.php' );
require_once( SIW_INCLUDES_DIR . '/settings/init.php' );


/* Diverse aanpassingen */
require_once( SIW_INCLUDES_DIR . '/custom.php' );
require_once( SIW_INCLUDES_DIR . '/head.php' );
require_once( SIW_INCLUDES_DIR . '/htaccess.php' );
require_once( SIW_INCLUDES_DIR . '/enqueue.php' );
require_once( SIW_INCLUDES_DIR . '/scheduler.php' );
require_once( SIW_INCLUDES_DIR . '/seo.php' );
require_once( SIW_INCLUDES_DIR . '/shortcodes/init.php' );
require_once( SIW_INCLUDES_DIR . '/translations.php' );


require_once( SIW_INCLUDES_DIR . '/admin/init.php' );
require_once( SIW_INCLUDES_DIR . '/ajax/init.php' );
require_once( SIW_INCLUDES_DIR . '/background-process/init.php' );
require_once( SIW_INCLUDES_DIR . '/analytics/init.php' );
require_once( SIW_INCLUDES_DIR . '/cookie-notification/init.php' );
require_once( SIW_INCLUDES_DIR . '/email/init.php');
require_once( SIW_INCLUDES_DIR . '/forms/init.php' );
require_once( SIW_INCLUDES_DIR . '/maps/init.php' );
require_once( SIW_INCLUDES_DIR . '/pagebuilder/init.php' );
require_once( SIW_INCLUDES_DIR . '/post-types/init.php' );
require_once( SIW_INCLUDES_DIR . '/social-share/init.php' );
require_once( SIW_INCLUDES_DIR . '/topbar/init.php' );
require_once( SIW_INCLUDES_DIR . '/widgets/init.php' );
require_once( SIW_INCLUDES_DIR . '/woocommerce/init.php' );
require_once( SIW_INCLUDES_DIR . '/workcamps/init.php' );
