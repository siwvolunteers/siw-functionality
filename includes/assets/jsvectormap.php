<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Jsvectormap
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://jvm-docs.vercel.app/
 */
class Jsvectormap implements Script, Style {

	/** Versienummer */
	const VERSION = '1.5.2';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'frappe-charts';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/jsvectormap/jsvectormap.js', [], self::VERSION, true );
	}

	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/jsvectormap/jsvectormap.css', [], self::VERSION );
	}
}
