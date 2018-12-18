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
require_once( __DIR__ . '/class-siw-caldera-forms.php' );
require_once( __DIR__ . '/class-siw-mailpoet.php' );
require_once( __DIR__ . '/class-siw-password-protected.php' );
require_once( __DIR__ . '/class-siw-pinnacle-premium.php' );
require_once( __DIR__ . '/class-siw-siteorigin-page-builder.php' );
require_once( __DIR__ . '/class-siw-the-seo-framework.php' );
require_once( __DIR__ . '/class-siw-updraftplus.php' );
require_once( __DIR__ . '/class-siw-woocommerce.php' );
require_once( __DIR__ . '/class-siw-wordpress.php' );
require_once( __DIR__ . '/class-siw-wp-rocket.php' );
require_once( __DIR__ . '/class-siw-yith.php' );

add_action( 'plugins_loaded', [ 'SIW_Caldera_Forms', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Mailpoet', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Password_Protected', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_Pinnacle_Premium', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_SiteOrigin_Page_Builder', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_The_SEO_Framework', 'init' ]) ;
add_action( 'plugins_loaded', [ 'SIW_UpdraftPlus', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_WooCommerce', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_WordPress', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_WP_Rocket', 'init' ] );
add_action( 'plugins_loaded', [ 'SIW_YITH', 'init' ] );



/*
 * Safe Redirect Manager
 * - Aantal toegestane redirects + standaard statuscode aanpassen
 */
add_filter( 'srm_max_redirects', function() { return 250; } );
add_filter( 'srm_default_direct_status', function() { return 301; } );


/*
 * Strong Testimonials
 *
 * - Permalink aanpassen van 'testimonial' naar 'ervaring'
 * TODO: Kan weg na vervangen plugin "Strong Testimonials" door eigen functionaliteit
 */
add_filter( 'wpmtst_post_type', function( $args ) {
	$args['rewrite']['slug'] = 'ervaring';
	return $args;
} );

/**	
 * Redux Framework
 *
 * - Dashboard metabox verwijderen:
 */
add_action( 'do_meta_boxes', function() {
	remove_meta_box( 'redux_dashboard_widget', 'dashboard', 'side' );
});



/* Widgets opschonen */
add_action( 'widgets_init', function() {
	/* WPML */
	unregister_widget( 'WPML_LS_Widget' );
}, 99 );


/* IP whitelist voor plugin 'Limit Login Attempts' */
add_filter( 'limit_login_whitelist_ip', function( $allow, $ip ) {
	$ip_whitelist = siw_get_ip_whitelist();
	if ( in_array( $ip, $ip_whitelist ) ) {
		$allow = true;
	}
	return $allow;
}, 99, 2 );