<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://mciastek.github.io/sal/
 */
class Sal extends NPM_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return '0.8.5';
	}

	#[\Override]
	protected static function get_npm_package(): string {
		return 'sal.js';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/sal.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/sal.css';
	}
}
