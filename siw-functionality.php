<?php
/*
 * Plugin Name: SIW Functionaliteit
 * Plugin URI: https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Author: Maarten Bruna
 * Version: 1.4
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
define ( 'SIW_PLUGIN_VERSION', '1.3.1' );
define ( 'SIW_AJAX_URL', SIW_PLUGIN_URL . 'ajax-handler.php' );
define ( 'SIW_SITE_URL', get_site_url() );
define ( 'SIW_SITE_NAME', $_SERVER['SERVER_NAME'] );



/*
 * Hulp-plugins
 * - WP MultiFilter (https://github.com/khromov/wp-multifilter)
 * - Disable Emoji's (https://geek.hellyer.kiwi/plugins/disable-emojis/)
 * - Rapid Add-On (https://github.com/soflyy/wp-all-import-rapid-addon)
 * - WordPress Widgets Helper Class (https://github.com/alessandrotesoro/wp-widgets-helper)
 */
require_once( SIW_ASSETS_DIR . '/plugins/wp-multifilter.php' );
require_once( SIW_ASSETS_DIR . '/plugins/disable-emojis.php' );
require_once( SIW_ASSETS_DIR . '/plugins/rapid-addon.php' );
require_once( SIW_ASSETS_DIR . '/plugins/wp-widgets-helper.php');



/* Referentiegegevens */
require_once( SIW_INCLUDES_DIR . '/reference-data/init.php' );
/* Getters */
require_once( SIW_INCLUDES_DIR . '/getters.php' );
/* Instellingen */
require_once( SIW_INCLUDES_DIR . '/settings/init.php' );



/* Diverse aanpassingen */
require_once( SIW_INCLUDES_DIR . '/custom.php' );
/* JS en CSS enqueuen */
require_once( SIW_INCLUDES_DIR . '/enqueue.php' );
/* Cron jobs schedulen */
require_once( SIW_INCLUDES_DIR . '/scheduler.php' );
/* Zoekfunctionaliteit */
require_once( SIW_INCLUDES_DIR . '/search.php' );
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
/*Google Analytics */
require_once( SIW_INCLUDES_DIR . '/analytics/init.php' );
/*E-mail*/
require_once( SIW_INCLUDES_DIR . '/email/init.php');
/* Formulieren */
require_once( SIW_INCLUDES_DIR . '/forms/init.php' );
/* Pagebuilder */
require_once( SIW_INCLUDES_DIR . '/pagebuilder/init.php' );
/* Custom post types */
require_once( SIW_INCLUDES_DIR . '/post-types/init.php' );
/* Social share links */
require_once( SIW_INCLUDES_DIR . '/social-share/init.php' );
/* Topbar met eerstvolgende evenement */
require_once( SIW_INCLUDES_DIR . '/topbar/init.php' );
/* Widgets */
require_once( SIW_INCLUDES_DIR . '/widgets/init.php' );
/* WooCommerce */
require_once( SIW_INCLUDES_DIR . '/woocommerce/init.php' );
