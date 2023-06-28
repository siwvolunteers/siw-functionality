<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
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

	#[Filter( 'siteorigin_panels_layouts_directory_enabled' )]
	private const ENABLE_LAYOUTS_DIRECTORY = false;

	#[Filter( 'so_panels_show_add_new_dropdown_for_type' )]
	private const SHOW_ADD_NEW_DROPDOWN_FOR_TYPE = false;

	#[Filter( 'siteorigin_add_installer' )]
	private const SHOW_SITEORIGIN_INSTALLER = false;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'siteorigin-panels/siteorigin-panels.php';
	}

	#[Action( 'admin_init' )]
	/** Verwijdert dashboard widget */
	public function remove_dashboard_widget() {
		remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
	}

	#[Action( 'widgets_init', 99 )]
	/** Verwijdert Page Builder widgets */
	public function unregister_widgets() {
		unregister_widget( \SiteOrigin_Panels_Widgets_PostContent::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_PostLoop::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Layout::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Gallery::class );
	}

	#[Filter( 'siteorigin_panels_widget_dialog_tabs' )]
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

	#[Filter( 'siteorigin_panels_settings' )]
	/** Zet breakpoint-instellingen */
	public function set_breakpoint_settings( array $settings ): array {
		$settings['mobile-width'] = CSS::MOBILE_BREAKPOINT;
		$settings['tablet-width'] = CSS::TABLET_BREAKPOINT;
		return $settings;
	}
}
