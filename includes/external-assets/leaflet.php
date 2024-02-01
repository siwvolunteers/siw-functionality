<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * @see https://leafletjs.com/
 */
class Leaflet extends NPM_Asset {

	#[\Override]
	protected static function get_npm_package(): string {
		return 'leaflet';
	}

	#[\Override]
	protected static function get_version_number(): ?string {
		return '1.9.4';
	}

	#[\Override]
	protected static function get_style_file(): ?string {
		return 'dist/leaflet.css';
	}

	#[\Override]
	protected static function get_script_file(): ?string {
		return 'dist/leaflet.js';
	}
}
