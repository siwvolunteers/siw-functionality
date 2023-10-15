<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Cookie consent
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://github.com/orestbida/cookieconsent
 */
class Cookie_Consent extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'vanilla-cookieconsent';
	}

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '2.9.1';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/cookieconsent.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/cookieconsent.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-H+Z1mZeulbIwdqtQq6Vgn6y6yr33+pzXlDd13s3dLh4=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-LO7u2UQLQZYZwftu3mP+YM/VfUZES3Ob0daqb5yz2rE=';
	}
}