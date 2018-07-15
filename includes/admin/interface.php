<?php
/*
 * (c)2017-2018 SIW Internationale Vrijwilligersprojecten
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
 * - Nieuw bericht
 * - Updraft Plus
 */
add_action( 'wp_before_admin_bar_render', function() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'site-name' );
	$wp_admin_bar->remove_node( 'ktoptions' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'new-content' );
}, 999 );

/* Kolommen verbergen bij overzicht pagina's */
add_filter( 'manage_pages_columns', function( $columns ) {
	unset( $columns['comments'] );
	unset( $columns['author'] );

	return $columns;
}, 10 );


/* Admin Bar acties*/ 
add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$nodes = apply_filters( 'siw_admin_bar_nodes', array() );
	$actions = apply_filters( 'siw_admin_bar_actions', array() );

	$referer = '&_wp_http_referer=' . rawurlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) );

	if ( empty( $actions ) ) {
		return;
	}

	/* Voeg hoofditem toe*/
	$args = array(
		'id'    => 'siw-actions',
		'title' => __( 'Start actie', 'siw' ),
		'href'  => '#',
	);
	$wp_admin_bar->add_node( $args );

	/* Voeg nodes toe */
	foreach ( $nodes as $node => $properties ) {
		$args = array(
			'parent' => ( isset( $properties['parent'] ) ) ? 'siw-' . $properties['parent'] . '-actions' : 'siw-actions',
			'id' => 'siw-' .$node . '-actions',
			'title' => $properties['title'],
		);
		$wp_admin_bar->add_node( $args );
	}

	/* Voeg acties toe */
	foreach ( $actions as $action => $properties ) {
		$args = array(
			'parent' => ( isset( $properties['parent'] ) ) ? 'siw-' . $properties['parent'] . '-actions' : 'siw-actions',
			'id' => 'siw-action-' . $action,
			'title' => $properties['title'],
			'href'   => wp_nonce_url( admin_url( 'admin-post.php?siw-action=' . $action . $referer ), 'siw-action' ),
		);
		$wp_admin_bar->add_node( $args );
	}

});


/* Verwerk actietrigger TODO: async request van maken*/
add_action( 'init', function () {
	if ( ! isset( $_GET['siw-action'] ) || ! isset( $_GET['_wpnonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'siw-action') ) {
		return;
	}
	$action = esc_attr( trim( $_REQUEST['siw-action']) );

	$actions = apply_filters( 'siw_admin_bar_actions', array() );
	
	if ( ! in_array( $action, array_keys( $actions ) ) ) {
		return;
	}

	do_action( 'siw_' . $action );
	$notices = new SIW_Transient_Notices();

	$notices->add_notice( 'success', sprintf( __( 'Proces gestart: %s', 'siw' ), $actions[ $action ]['title']), true );
	wp_redirect( wp_get_referer() );

});

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
	if ( ! current_user_can( 'manage_options' ) ) {
		remove_menu_page( 'wppusher');
	}
}, 99 );


/* Welcome panel verwijderen */
remove_action( 'welcome_panel', 'wp_welcome_panel' );


/* Standaard dashboard widgets verwijderen */
add_action( 'admin_init', function() {
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
});


/*
 * Metaboxes van plugins verwijderen:
 * - WooCommerce
 * - Redux Framework
 */
add_action( 'do_meta_boxes', function() {
	remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
	remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	remove_meta_box( 'redux_dashboard_widget', 'dashboard', 'side' );
});


/* "WooCommerce" in menu vervangen door "Aanmeldingen" + Eigen icoon voor BBQ Pro en Pinnacle theme options*/
add_action( 'admin_menu', function() {
	global $menu;

	$woo = siw_menu_array_search( 'woocommerce', $menu );
	if ( $woo ) {
		$menu[ $woo ][0] = __( 'Aanmeldingen', 'siw' );
	}

	$bbq = siw_menu_array_search( 'bbq_settings', $menu );
	if ( $bbq ) {
		$menu[ $bbq ][6] = 'dashicons-shield-alt';
	}

	$kt = siw_menu_array_search( 'ktoptions', $menu );
	if ( $kt ) {
		$menu[ $kt ][6] ='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAZdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuMTZEaa/1AAAA+klEQVRIS+2SsQ7BUBSG27Bgl9jFILGJ3WgzeAlv4DXE0ERsVgw2D+Ad+gTewNDQ1nfqNKQJ7Y0Sw/2SP//Re85/3OJYLK+I47gahuEiiqKaPvoNLJ6hmOVzffR9uOUABbIYD9FIj8qF/IqWsrSBfF0qJrc+UTe1pTwIXaKJ1qt0oZDW+B5zk4EyILCLLioPPbZm4GgqM5T1ZPgTCNvcY/Oh94yNcR+1NMIchvv8fliUvFITMedpjBl8c5eAg97EWCwO8LbGFYehoQSYkO3n81rjisGM3PZouliQmSddUU9j86G5w6va5ok+0U78zXnyT7dYLBaL5d9xnBswdjMy+Bkh/AAAAABJRU5ErkJggg==';
	}

	return;
}, 999 );

function siw_menu_array_search( $find, $items ) {
	foreach ( $items as $key => $value ) {
		$current_key = $key;
		if ( $find === $value || ( is_array( $value ) && siw_menu_array_search( $find, $value ) !== false ) ) {
			return $current_key;
		}
	}
	return false;
}


/* YITH premium nags verwijderen */
add_filter( 'yit_plugin_panel_menu_page_show',  '__return_false' );

add_filter( 'yit_show_upgrade_to_premium_version',  '__return_false' );
add_filter( 'yith_wcan_settings_tabs', function( $admin_tabs ) {
	unset( $admin_tabs['premium'] );
	return $admin_tabs;
});


/* Copyright in admin footer */
add_filter( 'admin_footer_text', function() {
	printf( '&copy; %s %s', date( 'Y' ), SIW_NAME );
});
