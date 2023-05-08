<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Filter;

/**
 * Aanpassingen voor GTranslate
 *
 * @copyright   2023 SIW Internationale Vrijwilligersprojecten
 * @see         https://gtranslate.io/
 */
class GTranslate extends Plugin {

	/** {@inheritDoc} */
	protected static function get_plugin_path(): string {
		return 'gtranslate/gtranslate.php';
	}

	#[Filter( 'rocket_exclude_js' )]
	/** JS-bestanden uitsluiten van minification/concatenation */
	public function exclude_js( array $excluded_files ): array {
		$excluded_files[] = '/wp-content/plugins/gtranslate/js/(.*).js';
		return $excluded_files;
	}
}
