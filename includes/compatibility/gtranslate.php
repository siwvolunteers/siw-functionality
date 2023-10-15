<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * Aanpassingen voor GTranslate
 *
 * @copyright   2023 SIW Internationale Vrijwilligersprojecten
 * @see         https://gtranslate.io/
 */
class GTranslate extends Base implements I_Plugin {

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'gtranslate/gtranslate.php';
	}

	#[Add_Filter( 'rocket_exclude_js' )]
	/** JS-bestanden uitsluiten van minification/concatenation */
	public function exclude_js( array $excluded_files ): array {
		$excluded_files[] = '/wp-content/plugins/gtranslate/js/(.*).js';
		return $excluded_files;
	}
}
