<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Sal.js
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see https://mciastek.github.io/sal/
 */
class Sal implements Style, Script{

	/** Versienummer */
	const VERSION = '0.8.5';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'sal';

	/** {@inheritDoc} */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/sal.js/sal.css', [], self::VERSION );
	}

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/sal.js/sal.js', [], self::VERSION, true );
	}
}
