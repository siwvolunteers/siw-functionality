<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\External_Assets\NPM_Asset;

/**
 * @see https://github.com/themustafaomar/jsvectormap
 */
class Jsvectormap extends NPM_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return '1.5.3';
	}

	#[\Override]
	protected static function get_npm_package(): string {
		return 'jsvectormap';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/js/jsvectormap.min.js';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/css/jsvectormap.min.css';
	}
}
