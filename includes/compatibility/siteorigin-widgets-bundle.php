<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Filter;

/**
 * @see         https://siteorigin.com/widgets-bundle/
 */
class SiteOrigin_Widgets_Bundle extends Plugin {

	#[\Override]
	public static function get_plugin_basename(): string {
		return 'so-widgets-bundle/so-widgets-bundle.php';
	}

	#[Add_Filter( 'siteorigin_widgets_widget_folders' )]
	public function set_widget_folders(): array {
		return [ SIW_WIDGETS_DIR ];
	}
}
