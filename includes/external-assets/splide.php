<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://splidejs.com
 */
class Splide extends NPM_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return '4.1.4';
	}

	#[\Override]
	protected static function get_npm_package(): string {
		return '@splidejs/splide';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/js/splide.min.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/css/splide.min.css';
	}
}
