<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor WP Sentry Integration
 *
 * @copyright   2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class WP_Sentry_Integration {

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'wp-sentry-integration/wp-sentry.php' ) ) {
			return;
		}

		$self = new self();
		add_filter( 'rocket_exclude_js', [ $self, 'exclude_js' ] );
		add_filter( 'wp_sentry_public_context', [ $self, 'set_context' ] );
	}

	/** JS-bestanden uitsluiten van minification/concatenation */
	public function exclude_js( array $excluded_files ): array {
		$excluded_files[] = '/wp-content/plugins/wp-sentry-integration/public/(.*).js';
		return $excluded_files;
	}

	/** Zet taal op correcte waarde */
	public function set_context( array $context ): array {
		$context['tags']['language'] = determine_locale();
		return $context;
	}
}
