<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor SiteOrigin Page Builder
 * 
 * @copyright   2021 SIW Internationale Vrijwilligersprojecten
 * @see         https://siteorigin.com/widgets-bundle/
 */
class SiteOrigin_Widgets_Bundle {

	/** Init */
	public static function init() {
		
		if ( ! is_plugin_active( 'so-widgets-bundle/so-widgets-bundle.php' ) ) {
			return;
		}
		
		$self = new self();

		add_filter( 'siteorigin_widgets_widget_folders', [ $self, 'set_widget_folders' ] );
		add_filter( 'siteorigin_widgets_default_active', '__return_empty_array' );
		add_filter( 'siteorigin_panels_data', [ $self, 'handle_renamed_widgets'] );
	}

	/** Overschrijf SiteOrigin Widgets met SIW-widgets */
	public function set_widget_folders( array $widget_folders ) : array {
		$widget_folders = [ SIW_WIDGETS_DIR ];
		return apply_filters( 'siw_widget_folders', $widget_folders );
	}

	/** Hernoemde widgets corrigeren */
	public function handle_renamed_widgets( $panels_data ) {

		if ( ! is_array( $panels_data ) ) {
			return $panels_data;
		}
		
		foreach ( $panels_data['widgets'] as &$widget ) {
			if ( 0 === strpos( $widget['panels_info']['class'], 'SIW_Widget_' ) ) {
				$widget['panels_info']['class'] = str_replace( 'SIW_Widget_', "\\SIW\\Widgets\\", $widget['panels_info']['class'] );
			}
		}
		return $panels_data;
	}
}
