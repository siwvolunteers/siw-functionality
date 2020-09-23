<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor SiteOrigin Page Builder
 * 
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @see         https://siteorigin.com/page-builder/
 * @since       3.0.0
 */
class SiteOrigin_Page_Builder {

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( '\SiteOrigin_Panels' ) ) {
			return;
		}
		$self = new self();
		add_action( 'admin_init', [ $self, 'remove_dashboard_widget' ] );
		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], 99 );
		add_filter( 'siteorigin_panels_widget_dialog_tabs', [ $self, 'add_widget_tab'] );
		add_filter( 'siteorigin_panels_layouts_directory_enabled', '__return_false' );
	}

	/**
	 * Verwijdert dashboard widget
	 */
	public function remove_dashboard_widget() {
		remove_meta_box( 'so-dashboard-news', 'dashboard', 'normal' );
	}

	/**
	 * Verwijdert Page Builder widgets
	 */
	public function unregister_widgets() {
		unregister_widget( 'SiteOrigin_Panels_Widgets_PostContent' );
		unregister_widget( 'SiteOrigin_Panels_Widgets_PostLoop' );
		unregister_widget( 'SiteOrigin_Panels_Widgets_Layout' );
		unregister_widget( 'SiteOrigin_Panels_Widgets_Gallery' );
	}

	/**
	 * Voegt tab voor SIW-widgets toe
	 *
	 * @param array $tabs
	 * 
	 * @return array
	 */
	public function add_widget_tab( array $tabs ) : array {
		$tabs[] = [
			'title'  => __( 'SIW Widgets', 'siw' ),
			'filter' => [
				'groups' => [ 'siw' ],
			],
		];
		return $tabs;
	}
}
