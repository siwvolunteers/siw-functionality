<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor WP Sentry Integration
 * 
 * @copyright   2019 SIW Internationale Vrijwilligersprojecten
 * @since       3.0.0
 */
class WP_Sentry_Integration {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'rocket_exclude_js', [ $self, 'exclude_js' ] );
	}

	/** JS-bestanden uitsluiten van minification/concatenation */
	public function exclude_js( array $excluded_files ) : array {
		$excluded_files[] = '/wp-content/plugins/wp-sentry-integration/public/(.*).js';
		return $excluded_files;
	}
}
