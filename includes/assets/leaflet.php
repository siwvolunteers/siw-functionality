<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Leaflet
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://leafletjs.com/
 */
class Leaflet implements Style, Script {

	/** Versienummer */
	const VERSION = '1.9.4';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'leaflet';

	/** {@inheritDoc} */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/leaflet/leaflet.css', [], self::VERSION );
	}

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/leaflet/leaflet.js', [], self::VERSION, true );
	}
}
