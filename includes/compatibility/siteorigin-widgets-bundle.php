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
	}

	/** Overschrijf SiteOrigin Widgets met SIW-widgets */
	public function set_widget_folders(): array {
		return [ SIW_WIDGETS_DIR ];
	}
}
