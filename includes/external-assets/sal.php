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

	#[\Override]
	protected static function get_script_sri(): ?string {
		return 'sha256-PQTVYphQHI07L8/JEMW6OnjA0oBajuMduQENBAF7jeE=';
	}

	#[\Override]
	protected static function get_style_sri(): ?string {
		return 'sha256-WdGqbB/iPzlkcf1+GjZ94/0ju5V9VogwLGeqxciOUgU=';
	}
}
