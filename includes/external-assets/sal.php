<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://mciastek.github.io/sal/
 */
class Sal extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '0.8.5';
	}

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'sal.js';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/sal.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/sal.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-PQTVYphQHI07L8/JEMW6OnjA0oBajuMduQENBAF7jeE=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-WdGqbB/iPzlkcf1+GjZ94/0ju5V9VogwLGeqxciOUgU=';
	}
}
