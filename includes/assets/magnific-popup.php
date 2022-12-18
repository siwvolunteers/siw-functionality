<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Magnific Popup
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/dimsemenov/Magnific-Popup
 */
class Magnific_Popup implements Style, Script {

	/** Versienummer */
	const VERSION = '1.1.0';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'magnific-popup';

	/** Registreert style */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/mapplic/css/magnific-popup.css', [], self::VERSION );
	}

	/** Registreert script */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/mapplic/js/magnific-popup.js', [ 'jquery' ], self::VERSION, true );
	}
}
