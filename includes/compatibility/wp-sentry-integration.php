<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Filter;

class WP_Sentry_Integration extends Plugin {

	#[\Override]
	public static function get_plugin_basename(): string {
		return 'wp-sentry-integration/wp-sentry.php';
	}

	#[Add_Filter( 'wp_sentry_public_context' )]
	public function set_context( array $context ): array {
		$context['tags']['language'] = determine_locale();
		return $context;
	}
}
