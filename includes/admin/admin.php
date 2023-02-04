<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\User_Columns;
use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Properties;

/**
 * Aanpassingen aan Admin
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Admin extends Base {

	const ASSETS_HANDLE = 'siw-admin';

	#[Action( 'admin_init' )]
	/** Verwijdert Welcome Panel */
	public function remove_welcome_panel() {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	#[Action( 'admin_enqueue_scripts' )]
	/** Voegt admin-styling toe */
	public function enqueue_admin_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/admin/siw-admin.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	#[Action( 'admin_menu', PHP_INT_MAX )]
	/** Verwijdert standaard menu-items */
	public function hide_pages() {
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'link-manager.php' );
	}

	#[Action( 'admin_init' )]
	/** Verbergt standaard dashboard widgets */
	public function hide_dashboard_widgets() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	}

	#[Filter( 'admin_footer_text' )]
	/** Voegt copyright toe aan admin footer */
	public function set_admin_footer_text(): string {
		return sprintf( '&copy; %s %s', gmdate( 'Y' ), Properties::NAME );
	}

	#[Filter( 'manage_pages_columns' )]
	/** Verbergt admin-column voor pagina's */
	public function remove_pages_columns( array $columns ): array {
		unset( $columns['comments'] );
		unset( $columns['author'] );
		return $columns;
	}

	#[Action( 'admin_menu' )]
	/** Verwijdert diverse metaboxes */
	public function remove_metaboxes() {
		remove_meta_box( 'postcustom', [ 'page', 'post' ], 'normal' );
		remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', [ 'page', 'post', 'attachment' ], 'normal' );
		remove_meta_box( 'commentsdiv', [ 'page', 'post', 'attachment' ], 'normal' );
		remove_meta_box( 'slugdiv', [ 'page', 'post', 'attachment' ], 'normal' );
		remove_meta_box( 'authordiv', [ 'page', 'post', 'attachment' ], 'normal' );
	}

	#[Action( 'admin_init', 20 )]
	/** Voegt extra admin columns toe */
	public function add_user_columns() {
		if ( ! class_exists( '\MBAC\User' ) ) {
			return;
		}
		new User_Columns( 'user', [] );
	}
}
