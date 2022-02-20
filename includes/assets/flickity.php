<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Interfaces\Assets\Style;

/**
 * Flickity carousel
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see https://flickity.metafizzy.co/
 */
class Flickity implements Style, Script {

	/** Versienummer */
	const VERSION = '2.3.0';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'flickity';

	/** {@inheritDoc} */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/flickity/flickity.css', [], self::VERSION );
	}

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/flickity/flickity.pkgd.js', [], self::VERSION, true );
	}
}
