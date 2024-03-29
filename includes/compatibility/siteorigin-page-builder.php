<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Data\Breakpoint;
use SIW\Traits\Class_Assets;

/**
 * @see         https://siteorigin.com/page-builder/
 */
class SiteOrigin_Page_Builder extends Plugin {

	use Class_Assets;

	#[Add_Filter( 'siteorigin_panels_layouts_directory_enabled', PHP_INT_MAX )]
	private const ENABLE_LAYOUTS_DIRECTORY = false;

	#[Add_Filter( 'so_panels_show_add_new_dropdown_for_type', PHP_INT_MAX )]
	private const SHOW_ADD_NEW_DROPDOWN_FOR_TYPE = false;

	#[Add_Filter( 'siteorigin_add_installer', PHP_INT_MAX )]
	private const SHOW_SITEORIGIN_INSTALLER = false;

	#[Add_Filter( 'siteorigin_panels_theme_container_width' )]
	private const THEME_CONTAINER_WIDTH = '1280px';

	#[Add_Filter( 'siteorigin_panels_theme_container_selector' )]
	private const THEME_CONTAINER_SELECTOR = '.site.grid-container.container';

	#[\Override]
	public static function get_plugin_basename(): string {
		return 'siteorigin-panels/siteorigin-panels.php';
	}

	#[Add_Action( 'admin_init' )]
	public function remove_dashboard_widget() {
		remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
	}

	#[Add_Action( 'widgets_init', 99 )]
	public function unregister_widgets() {
		unregister_widget( \SiteOrigin_Panels_Widgets_PostContent::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_PostLoop::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Layout::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Gallery::class );
	}

	#[Add_Filter( 'siteorigin_panels_widget_dialog_tabs', 99 )]
	public function set_widget_dialog_tabs( array $tabs ): array {
		$tabs['siw'] = [
			'title'  => __( 'SIW Widgets', 'siw' ),
			'filter' => [
				'groups' => [ 'siw' ],
			],
		];
		unset( $tabs['page_builder'] );
		unset( $tabs['widgets_bundle'] );
		unset( $tabs['recommended'] );
		return $tabs;
	}

	#[Add_Filter( 'siteorigin_panels_settings' )]
	public function set_breakpoint_settings( array $settings ): array {
		$settings['mobile-width'] = Breakpoint::MOBILE->value;
		$settings['tablet-width'] = Breakpoint::TABLET->value;
		return $settings;
	}

	#[Add_Action( 'siteorigin_panels_after_render' )]
	public function enqueue_pagebuilder_styles() {
		self::enqueue_class_style();
	}
}
