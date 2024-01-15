<?php declare(strict_types=1);

namespace SIW\Admin;

use SIW\Admin\User_Columns;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Properties;
use SIW\Traits\Assets_Handle;

class Admin extends Base {

	use Assets_Handle;

	#[Add_Action( 'admin_init' )]
	public function remove_welcome_panel() {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	#[Add_Action( 'admin_enqueue_scripts' )]
	public function enqueue_admin_style() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/admin/siw-admin.css', [], SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::get_assets_handle() );
	}

	#[Add_Action( 'admin_menu', PHP_INT_MAX )]
	public function hide_pages() {
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'link-manager.php' );
	}

	#[Add_Action( 'admin_init' )]
	public function hide_dashboard_widgets() {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
	}

	#[Add_Filter( 'admin_footer_text' )]
	public function set_admin_footer_text(): string {
		return sprintf( '&copy; %s %s', gmdate( 'Y' ), Properties::NAME );
	}

	#[Add_Filter( 'manage_pages_columns' )]
	public function remove_pages_columns( array $columns ): array {
		unset( $columns['comments'] );
		unset( $columns['author'] );
		return $columns;
	}

	#[Add_Action( 'admin_menu' )]
	public function remove_metaboxes() {
		remove_meta_box( 'postcustom', [ 'page', 'post' ], 'normal' );
		remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
		remove_meta_box( 'commentstatusdiv', [ 'page', 'post', 'attachment' ], 'normal' );
		remove_meta_box( 'commentsdiv', [ 'page', 'post', 'attachment' ], 'normal' );
		remove_meta_box( 'slugdiv', [ 'page', 'post', 'attachment' ], 'normal' );
		remove_meta_box( 'authordiv', [ 'page', 'post', 'attachment' ], 'normal' );
	}

	#[Add_Action( 'admin_init', 20 )]
	public function add_user_columns() {
		if ( ! class_exists( '\MBAC\User' ) ) {
			return;
		}
		new User_Columns( 'user', [] );
	}
}
