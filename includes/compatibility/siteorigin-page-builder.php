<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Util\CSS;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * Aanpassingen voor SiteOrigin Page Builder
 *
 * @copyright   2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see         https://siteorigin.com/page-builder/
 */
class SiteOrigin_Page_Builder extends Base implements I_Plugin {

	#[Add_Filter( 'siteorigin_panels_layouts_directory_enabled', PHP_INT_MAX )]
	private const ENABLE_LAYOUTS_DIRECTORY = false;

	#[Add_Filter( 'so_panels_show_add_new_dropdown_for_type', PHP_INT_MAX )]
	private const SHOW_ADD_NEW_DROPDOWN_FOR_TYPE = false;

	#[Add_Filter( 'siteorigin_add_installer', PHP_INT_MAX )]
	private const SHOW_SITEORIGIN_INSTALLER = false;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'siteorigin-panels/siteorigin-panels.php';
	}

	#[Add_Action( 'admin_init' )]
	/** Verwijdert dashboard widget */
	public function remove_dashboard_widget() {
		remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
	}

	#[Add_Action( 'widgets_init', 99 )]
	/** Verwijdert Page Builder widgets */
	public function unregister_widgets() {
		unregister_widget( \SiteOrigin_Panels_Widgets_PostContent::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_PostLoop::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Layout::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Gallery::class );
	}

	#[Add_Filter( 'siteorigin_panels_widget_dialog_tabs' )]
	/** Voegt tab voor SIW-widgets toe */
	public function add_widget_tab( array $tabs ): array {
		$tabs[] = [
			'title'  => __( 'SIW Widgets', 'siw' ),
			'filter' => [
				'groups' => [ 'siw' ],
			],
		];
		return $tabs;
	}

	#[Add_Filter( 'siteorigin_panels_settings' )]
	/** Zet breakpoint-instellingen */
	public function set_breakpoint_settings( array $settings ): array {
		$settings['mobile-width'] = CSS::MOBILE_BREAKPOINT;
		$settings['tablet-width'] = CSS::TABLET_BREAKPOINT;
		return $settings;
	}
}
