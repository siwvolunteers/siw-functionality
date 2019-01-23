<?php
/**
 * Aanpassingen t.b.v. andere plugins
 * 
 * @author      Maarten Bruna
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// TODO: bootstrap
require_once( __DIR__ . '/class-siw-compat.php' );
require_once( __DIR__ . '/class-siw-compat-caldera-forms.php' );
require_once( __DIR__ . '/class-siw-compat-mailpoet.php' );
require_once( __DIR__ . '/class-siw-compat-meta-box.php' );
require_once( __DIR__ . '/class-siw-compat-password-protected.php' );
require_once( __DIR__ . '/class-siw-compat-pinnacle-premium.php' );
require_once( __DIR__ . '/class-siw-compat-siteorigin-page-builder.php' );
require_once( __DIR__ . '/class-siw-compat-the-seo-framework.php' );
require_once( __DIR__ . '/class-siw-compat-updraftplus.php' );
require_once( __DIR__ . '/class-siw-compat-woocommerce.php' );
require_once( __DIR__ . '/class-siw-compat-wordpress.php' );
require_once( __DIR__ . '/class-siw-compat-wp-rocket.php' );
require_once( __DIR__ . '/class-siw-compat-yith-wcan.php' );

add_action( 'plugins_loaded', [ 'SIW_Compat', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_Caldera_Forms', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_Mailpoet', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_Meta_Box', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_Password_Protected', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_Pinnacle_Premium', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_SiteOrigin_Page_Builder', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_The_SEO_Framework', 'init' ]) ;
add_action( 'plugins_loaded', [ 'SIW_Compat_UpdraftPlus', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_WooCommerce', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_WordPress', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_WP_Rocket', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Compat_YITH_WCAN', 'init' ] );