<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;

/**
 * Aanpassingen voor WP Sentry Integration
 *
 * @copyright   2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class WP_Sentry_Integration extends Base implements I_Plugin {

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'wp-sentry-integration/wp-sentry.php';
	}

	#[Add_Filter( 'rocket_exclude_js' )]
	/** JS-bestanden uitsluiten van minification/concatenation */
	public function exclude_js( array $excluded_files ): array {
		$excluded_files[] = '/wp-content/plugins/wp-sentry-integration/public/(.*).js';
		return $excluded_files;
	}

	#[Add_Filter( 'wp_sentry_public_context' )]
	/** Zet taal op correcte waarde */
	public function set_context( array $context ): array {
		$context['tags']['language'] = determine_locale();
		return $context;
	}
}
