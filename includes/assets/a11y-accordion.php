<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * AcceDe Web accordion
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://github.com/AcceDe-Web/accordion
 */
class A11Y_Accordion implements Script {

	/** Versienummer */
	const VERSION = '1.1.0';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'a11y-accordion';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/accordion/accordion.js', [], self::VERSION, true );
	}
}
