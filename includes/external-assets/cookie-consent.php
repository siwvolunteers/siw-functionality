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
		return '2.9.2';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/cookieconsent.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/cookieconsent.css';
	}

	#[\Override]
	protected static function get_script_sri(): ?string {
		return 'sha256-heJUSWR0ojrfDjAAp4CiIxsBDpY1HYO7vZNYxQQ0llw=';
	}

	#[\Override]
	protected static function get_style_sri(): ?string {
		return 'sha256-LO7u2UQLQZYZwftu3mP+YM/VfUZES3Ob0daqb5yz2rE=';
	}
}
