<?php
/*
 * Plugin Name: SIW Functionaliteit
 * Plugin URI: https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Author: Maarten Bruna
 * Version: 1.5.1
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
define ( 'SIW_INCLUDES_DIR', SIW_PLUGIN_DIR . '/includes' );
define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define ( 'SIW_ASSETS_URL', SIW_PLUGIN_URL . 'assets/' );
define ( 'SIW_PLUGIN_VERSION', '1.5.1' );
define ( 'SIW_AJAX_URL', SIW_PLUGIN_URL . 'ajax-handler.php' );
define ( 'SIW_SITE_URL', get_site_url() );
define ( 'SIW_SITE_NAME', $_SERVER['SERVER_NAME'] );


/*
 * Hulp-plugins
 * - WP MultiFilter (https://github.com/khromov/wp-multifilter)
 * - Rapid Add-On (https://github.com/soflyy/wp-all-import-rapid-addon)
 * - WordPress Widgets Helper Class (https://github.com/alessandrotesoro/wp-widgets-helper)
 * - WP Background Processing (https://github.com/A5hleyRich/wp-background-processing)
 * - wp_parse_args_recursive (https://github.com/kallookoo/wp_parse_args_recursive)
 */
require_once( SIW_ASSETS_DIR . '/plugins/wp-multifilter.php' );
require_once( SIW_ASSETS_DIR . '/plugins/rapid-addon.php' );
require_once( SIW_ASSETS_DIR . '/plugins/wp-widgets-helper.php' );
require_once( SIW_ASSETS_DIR . '/plugins/wp-async-request.php' );
require_once( SIW_ASSETS_DIR . '/plugins/wp-background-process.php' );
require_once( SIW_ASSETS_DIR . '/plugins/wp-parse-args-recursive.php' );


/* Referentiegegevens */
require_once( SIW_INCLUDES_DIR . '/reference-data/init.php' );
/* Functies */
require_once( SIW_INCLUDES_DIR . '/functions/init.php' );
/* Instellingen */
require_once( SIW_INCLUDES_DIR . '/settings/init.php' );


/* Diverse aanpassingen */
require_once( SIW_INCLUDES_DIR . '/custom.php' );
/* .htaccess-aanpassingen */
require_once( SIW_INCLUDES_DIR . '/htaccess.php' );
/* JS en CSS enqueuen */
require_once( SIW_INCLUDES_DIR . '/enqueue.php' );
/* Cron jobs schedulen */
require_once( SIW_INCLUDES_DIR . '/scheduler.php' );
/* SEO */
require_once( SIW_INCLUDES_DIR . '/seo.php' );
/* Shortcodes */
require_once( SIW_INCLUDES_DIR . '/shortcodes/init.php' );
/* Vertalingen */
require_once( SIW_INCLUDES_DIR . '/translations.php' );


/* Admin */
require_once( SIW_INCLUDES_DIR . '/admin/init.php' );
/* AJAX-functionaliteit */
require_once( SIW_INCLUDES_DIR . '/ajax/init.php' );
/* Achtergrondprocessen */
require_once( SIW_INCLUDES_DIR . '/background-process/init.php' );
/*Google Analytics */
require_once( SIW_INCLUDES_DIR . '/analytics/init.php' );
/*E-mail*/
require_once( SIW_INCLUDES_DIR . '/email/init.php');
/* Formulieren */
require_once( SIW_INCLUDES_DIR . '/forms/init.php' );
/* Kaarten */
require_once( SIW_INCLUDES_DIR . '/maps/init.php' );
/* Pagebuilder */
require_once( SIW_INCLUDES_DIR . '/pagebuilder/init.php' );
/* Custom post types */
require_once( SIW_INCLUDES_DIR . '/post-types/init.php' );
/* Social share links */
require_once( SIW_INCLUDES_DIR . '/social-share/init.php' );
/* Topbar */
require_once( SIW_INCLUDES_DIR . '/topbar/init.php' );
/* Widgets */
require_once( SIW_INCLUDES_DIR . '/widgets/init.php' );
/* WooCommerce */
require_once( SIW_INCLUDES_DIR . '/woocommerce/init.php' );
