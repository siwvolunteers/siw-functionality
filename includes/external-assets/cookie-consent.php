<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://github.com/orestbida/cookieconsent
 */
class Cookie_Consent extends NPM_Asset {

	#[\Override]
	protected static function get_npm_package(): string {
		return 'vanilla-cookieconsent';
	}

	#[\Override]
	protected static function get_version_number(): ?string {
		return '3.0.0';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/cookieconsent.umd.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/cookieconsent.css';
	}
}
