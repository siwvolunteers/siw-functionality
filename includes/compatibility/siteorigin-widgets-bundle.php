<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Filter;

/**
 * Aanpassingen voor SiteOrigin Page Builder
 *
 * @copyright   2021 SIW Internationale Vrijwilligersprojecten
 * @see         https://siteorigin.com/widgets-bundle/
 */
class SiteOrigin_Widgets_Bundle extends Plugin {

	/** {@inheritDoc} */
	protected static function get_plugin_path(): string {
		return 'so-widgets-bundle/so-widgets-bundle.php';
	}

	#[Filter( 'siteorigin_widgets_widget_folders' )]
	/** Overschrijf SiteOrigin Widgets met SIW-widgets */
	public function set_widget_folders(): array {
		return [ SIW_WIDGETS_DIR ];
	}
}
