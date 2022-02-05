<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Style;

/**
 * Balloon.css
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @see https://kazzkiq.github.io/balloon.css/
 */
class Balloon implements Style {

	/** Versienummer */
	const VERSION = '1.2.0';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'balloon';

	/** {@inheritDoc */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/balloon-css/balloon.css', [], self::VERSION );
	}
}
