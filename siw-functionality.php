<?php
/*
 * Plugin Name: SIW Functionaliteit
 * Plugin URI: https://github.com/siwvolunteers/siw-functionality
 * Description: Extra functionaliteit t.b.v website SIW
 * Author: Maarten Bruna
 * Version: 1.0
 */


/*
 * Definieer constantes m.b.t. plugin
 * - Directory
 * - URL
 * - Versie
 * - URL AJAX-handler
 */
define ( 'SIW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define ( 'SIW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define ( 'SIW_PLUGIN_VERSION', '1.0' );
define ( 'SIW_AJAX_URL', SIW_PLUGIN_URL . 'ajax-handler.php' );


/*
 * Hulp-plugins
 * - TGMPA (http://tgmpluginactivation.com/)
 * - WP MultiFilter (https://github.com/khromov/wp-multifilter)
 * - Disable Emoji's (https://geek.hellyer.kiwi/plugins/disable-emojis/)
 * - Rapid Add-On (https://github.com/soflyy/wp-all-import-rapid-addon)
 */
require_once( 'assets/plugins/class-tgm-plugin-activation.php' );
require_once( 'assets/plugins/wp-multifilter.php' );
require_once( 'assets/plugins/disable-emojis.php' );
require_once( 'assets/plugins/rapid-addon.php' );


/* Benodigde plugins via TGMPA */
require_once( 'includes/required-plugins.php' );
/* Referentiegegevens */
require_once( 'includes/reference-data/init.php' );
/* Instellingen */
require_once( 'includes/settings/init.php' );
/* Getters */
require_once( 'includes/getters.php' );


/* Diverse aanpassingen */
require_once( 'includes/custom.php' );
/* JS en CSS enqueuen */
require_once( 'includes/enqueue.php' );
/* Cron jobs schedulen */
require_once( 'includes/scheduler.php' );
/* Zoekfunctionaliteit */
require_once( 'includes/search.php' );
/* Shortcodes */
require_once( 'includes/shortcodes/init.php' );
/* Vertalingen */
require_once( 'includes/translations.php' );


/* Admin */
require_once( 'includes/admin/init.php' );
/* AJAX-functionaliteit */
require_once( 'includes/ajax/init.php' );
/*Google Analytics */
require_once( 'includes/analytics/init.php' );
/* Pagebuilder */
require_once( 'includes/pagebuilder/init.php' );
/* Custom post types */
require_once( 'includes/post-types/init.php' );
/* Social share links */
require_once( 'includes/social-share/init.php' );
/* Topbar met eerstvolgende evenement */
require_once( 'includes/topbar/init.php' );
/* Widgets */
require_once( 'includes/widgets/init.php' );
/* WooCommerce */
require_once( 'includes/woocommerce/init.php' );
