<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen aan Admin
 * 
 * @package   SIW\Admin
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Admin {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		
		add_action( 'admin_enqueue_scripts', [ $self, 'enqueue_admin_style'] );

		add_action( 'admin_menu', [ $self, 'hide_pages'], PHP_INT_MAX );
		add_action( 'admin_init', [ $self, 'hide_dashboard_widgets'] );
		add_filter( 'admin_footer_text', [ $self, 'set_admin_footer_text'] );
		add_filter( 'manage_pages_columns', [ $self, 'remove_pages_columns'] );
		add_action( 'admin_menu', [ $self, 'remove_page_metaboxes' ] ); 
		add_action( 'admin_menu', [ $self, 'change_menu_items' ], PHP_INT_MAX );

		/* Welcome panel verwijderen */
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		/* Admin bar niet tonen in frontend voor ingelogde gebruikers */
		add_filter( 'show_admin_bar', '__return_false' );
		remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
	}

	/**
	 * Voegt admin-styling toe
	 */
	function enqueue_admin_style() {
		wp_register_style( 'siw-admin', SIW_ASSETS_URL . 'css/siw-admin.css' );
		wp_enqueue_style( 'siw-admin' );
	}

	/**
	 * Verwijdert standaard menu-items
	 */
	public function hide_pages() {
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'link-manager.php' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_menu_page( 'wppusher');
		}
	}

	/**
	 * Verbergt standaard dashboard widgets
	 */
	public function hide_dashboard_widgets() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	}
	
	/**
	 * Voegt copyright toe aan admin footer
	 *
	 * @param string $footer_text
	 * @return string
	 */
	public function set_admin_footer_text( $footer_text ) {
		$footer_text = sprintf( '&copy; %s %s', date( 'Y' ), SIW_Properties::get('name') );
		return $footer_text;
	}

	/**
	 * Verbergt admin-column voor pagina's
	 *
	 * @param array $columns
	 * @return array
	 */
	public function remove_pages_columns( $columns ) {
		unset( $columns['comments'] );
		unset( $columns['author'] );
		return $columns;
	}

	/**
	 * Verwijdert diverse metaboxes
	 */
	public function remove_page_metaboxes() {
		remove_meta_box( 'postcustom' , 'page' , 'normal' ); 
		remove_meta_box( 'commentstatusdiv' , 'page' , 'normal' ); 
		remove_meta_box( 'commentsdiv' , 'page' , 'normal' ); 
		remove_meta_box( 'slugdiv' , 'page' , 'normal' ); 
		remove_meta_box( 'authordiv' , 'page' , 'normal' ); 
	}

	/**
	 * Zoekt menu-item o.b.v. slug
	 *
	 * @param string $find
	 * @param array $items
	 * @return string
	 */
	protected function menu_search( $find, $items ) {
		foreach ( $items as $key => $value ) {
			$current_key = $key;
			if ( $find === $value || ( is_array( $value ) && $this->menu_search( $find, $value ) !== false ) ) {
				return $current_key;
			}
		}
		return false;
	}

	/**
	 * Past diverse menu-items aan
	 */
	public function change_menu_items() {
		global $menu;

		$woo = $this->menu_search( 'woocommerce', $menu );
		if ( $woo ) {
			$menu[ $woo ][0] = __( 'Aanmeldingen', 'siw' );
		}
	
		$bbq = $this->menu_search( 'bbq_settings', $menu );
		if ( $bbq ) {
			$menu[ $bbq ][6] = 'dashicons-shield-alt';
		}
	
		$kt = $this->menu_search( 'ktoptions', $menu );
		if ( $kt ) {
			$menu[ $kt ][6] ='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAZdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuMTZEaa/1AAAA+klEQVRIS+2SsQ7BUBSG27Bgl9jFILGJ3WgzeAlv4DXE0ERsVgw2D+Ad+gTewNDQ1nfqNKQJ7Y0Sw/2SP//Re85/3OJYLK+I47gahuEiiqKaPvoNLJ6hmOVzffR9uOUABbIYD9FIj8qF/IqWsrSBfF0qJrc+UTe1pTwIXaKJ1qt0oZDW+B5zk4EyILCLLioPPbZm4GgqM5T1ZPgTCNvcY/Oh94yNcR+1NMIchvv8fliUvFITMedpjBl8c5eAg97EWCwO8LbGFYehoQSYkO3n81rjisGM3PZouliQmSddUU9j86G5w6va5ok+0U78zXnyT7dYLBaL5d9xnBswdjMy+Bkh/AAAAABJRU5ErkJggg==';
		}
	}
}