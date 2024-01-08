<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Cookie consent
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://www.websitecarbon.com/badge/
 */
class Carbon_Badge extends NPM_Asset {

	protected static function get_npm_package(): string {
		return 'website-carbon-badges';
	}

	protected static function get_script_file(): ?string {
		return 'b.min.js';
	}

	protected static function get_style_file(): ?string {
		return null;
	}

	protected static function get_script_sri(): ?string {
		return 'sha256-k8tCeevdQf1TeaFQYSKwq/q7vZjKm+gkEO09dUIx3Ow=';
	}

	protected static function get_style_sri(): ?string {
		return null;
	}

	protected static function get_version_number(): ?string {
		return '1.1.3';
	}
}
