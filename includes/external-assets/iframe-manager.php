<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://github.com/orestbida/iframemanager
 */
class Iframe_Manager extends NPM_Asset {

	#[\Override]
	protected static function get_npm_package(): string {
		return '@orestbida/iframemanager';
	}

	#[\Override]
	protected static function get_version_number(): ?string {
		return '1.2.5';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/iframemanager.css';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/iframemanager.js';
	}
}
