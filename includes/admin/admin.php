<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\User_Columns;
use SIW\Properties;

/**
 * Aanpassingen aan Admin
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Admin {

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'admin_enqueue_scripts', [ $self, 'enqueue_admin_style' ] );
		add_action( 'admin_menu', [ $self, 'hide_pages' ], PHP_INT_MAX );
		add_action( 'admin_init', [ $self, 'hide_dashboard_widgets' ] );
		add_filter( 'admin_footer_text', [ $self, 'set_admin_footer_text' ] );
		add_filter( 'manage_pages_columns', [ $self, 'remove_pages_columns' ] );
		add_action( 'admin_menu', [ $self, 'remove_metaboxes' ] );
		add_filter( 'show_admin_bar', '__return_false' );
		add_action( 'admin_init', [ $self, 'add_user_columns' ], 20 );

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	/** Voegt admin-styling toe */
	public function enqueue_admin_style() {
		wp_register_style( 'siw-admin', SIW_ASSETS_URL . 'css/admin/siw-admin.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-admin' );
	}

	/** Verwijdert standaard menu-items */
	public function hide_pages() {
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'link-manager.php' );
	}

	/** Verbergt standaard dashboard widgets */
	public function hide_dashboard_widgets() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	}

	/** Voegt copyright toe aan admin footer */
	public function set_admin_footer_text(): string {
		return sprintf( '&copy; %s %s', gmdate( 'Y' ), Properties::NAME );
	}

	/** Verbergt admin-column voor pagina's */
	public function remove_pages_columns( array $columns ): array {
		unset( $columns['comments'] );
		unset( $columns['author'] );
		return $columns;
	}

	/** Verwijdert diverse metaboxes */
	public function remove_metaboxes() {
		remove_meta_box( 'postcustom', [ 'page', 'post' ], 'normal' );
		remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', [ 'page', 'post' ], 'normal' );
		remove_meta_box( 'commentsdiv', [ 'page', 'post' ], 'normal' );
		remove_meta_box( 'slugdiv', [ 'page', 'post' ], 'normal' );
		remove_meta_box( 'authordiv', [ 'page', 'post' ], 'normal' );
	}

	/** Voegt extra admin columns toe */
	public function add_user_columns() {
		if ( ! class_exists( '\MBAC\User' ) ) {
			return;
		}
		new User_Columns( 'user', [] );
	}
}
