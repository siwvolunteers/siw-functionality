<?php declare(strict_types=1);

namespace SIW\External_Assets;

/**
 * Leaflet
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://leafletjs.com/
 */
class Leaflet extends NPM_Asset {

	/** {@inheritDoc} */
	protected static function get_npm_package(): string {
		return 'leaflet';
	}

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return '1.9.4';
	}

	/** {@inheritDoc} */
	protected static function get_style_file(): ?string {
		return 'dist/leaflet.css';
	}

	/** {@inheritDoc} */
	protected static function get_script_file(): ?string {
		return 'dist/leaflet.js';
	}

	/** {@inheritDoc} */
	protected static function get_script_sri(): ?string {
		return 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
	}

	/** {@inheritDoc} */
	protected static function get_style_sri(): ?string {
		return 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
	}
}
