<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Util\CSS;

/**
 * Aanpassingen voor SiteOrigin Page Builder
 *
 * @copyright   2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see         https://siteorigin.com/page-builder/
 */
class SiteOrigin_Page_Builder {

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'siteorigin-panels/siteorigin-panels.php' ) ) {
			return;
		}

		$self = new self();
		add_action( 'admin_init', [ $self, 'remove_dashboard_widget' ] );
		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], 99 );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', [ $self, 'add_widget_tab' ] );
		add_filter( 'siteorigin_panels_layouts_directory_enabled', '__return_false' );
		add_filter( 'siteorigin_panels_settings', [ $self, 'set_breakpoint_settings' ] );
	}

	/** Verwijdert dashboard widget */
	public function remove_dashboard_widget() {
		remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
	}

	/** Verwijdert Page Builder widgets */
	public function unregister_widgets() {
		unregister_widget( \SiteOrigin_Panels_Widgets_PostContent::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_PostLoop::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Layout::class );
		unregister_widget( \SiteOrigin_Panels_Widgets_Gallery::class );
	}

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

	/** Zet breakpoint-instellingen */
	public function set_breakpoint_settings( array $settings ): array {
		$settings['mobile-width'] = CSS::MOBILE_BREAKPOINT;
		$settings['tablet-width'] = CSS::TABLET_BREAKPOINT;
		return $settings;
	}
}
