<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* Admin bar niet tonen in frontend voor ingelogde gebruikers */
add_filter( 'show_admin_bar', '__return_false' );


/*
 * Admin Bar nodes toevoegen
 * - SIW-logo
 * - URL
 */
add_action( 'admin_bar_menu', function( $wp_admin_bar ) {
	$logo_args = array(
		'id'	=> 'siw-logo',
		'meta'	=> array(
			'class' => 'siw-logo',
			'title' => 'SIW',
		),
	);
	$wp_admin_bar->add_node( $logo_args );

	$url_args = array(
		'id'	=> 'siw-url',
		'title'	=> sprintf( __( 'Je bent ingelogd op: %s', 'siw' ), site_url( '', '' ) ),
	);
	$wp_admin_bar->add_node( $url_args );
}, 1 );


/*
 * Admin Bar nodes verbergen
 * - WP-logo
 * - Sitenaam
 * - Opties Pinnacle Premium
 * - Comments
 * - Yoast
 * - VFB Pro
 * - Nieuw bericht
 * - Updraft Plus
 */
add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'site-name' );
	$wp_admin_bar->remove_node( 'ktoptions' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'wpseo-menu' );
	$wp_admin_bar->remove_node( 'vfbp-admin-toolbar' );
	$wp_admin_bar->remove_node( 'new-content' );
	$wp_admin_bar->remove_node( 'updraft_admin_node' );
}, 999 );


/*
 * Menu-items verwijderen
 * - Edit comments
 * - Edit posts
 * - Edit links
 */
add_action( 'admin_menu', function() {
	remove_menu_page( 'edit-comments.php' );
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'link-manager.php' );
});


/* Welcome panel verwijderen */
remove_action( 'welcome_panel', 'wp_welcome_panel' );


/* Standaard dashboard widgets verwijderen */
add_action( 'admin_init', function() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
});


/*
 * Metaboxes van plugins verwijderen:
 * - WooCommerce
 * - Yoast
 * - VFB Pro
 */
add_action( 'do_meta_boxes', function() {
	remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
	remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );
	remove_meta_box( 'vfbp-dashboard', 'dashboard', 'normal' );
	remove_meta_box( 'redux_dashboard_widget', 'dashboard', 'side' );
});


/* "WooCommerce" in menu vervangen door "Aanmeldingen" */
add_action( 'admin_menu', function() {
	global $menu;
	$woo = siw_menu_array_search( 'WooCommerce', $menu );
	if ( ! $woo ) {
		return;
	}
	$menu[ $woo ][0] = __( 'Aanmeldingen', 'siw' );
}, 999 );

function siw_menu_array_search( $find, $items ) {
	foreach ( $items as $key => $value ) {
		$current_key = $key;
		if ( $find === $value OR ( is_array( $value ) && siw_menu_array_search( $find, $value ) !== false ) ) {
			return $current_key;
		}
	}
	return false;
}


/* YITH premium nags verwijderen */
add_filter( 'yit_show_upgrade_to_premium_version',  '__return_false' );
add_filter( 'yit_panel_sidebar_load_remote_widgets',  '__return_false' );
add_filter( 'yit_panel_hide_sidebar', '__return_true' );
add_filter( 'yith_wcan_settings_tabs', function( $admin_tabs ) {
	unset( $admin_tabs['premium'] );

	return $admin_tabs;
});


/* Yoast box onderaan pagina */
add_filter( 'wpseo_metabox_prio', function() { return 'low'; } );


/* Copyright in admin footer */
add_filter( 'admin_footer_text', function() {
	printf( esc_html__( '&copy; 2015-%s %s', 'siw' ), date( 'Y' ), SIW_NAME );
});
