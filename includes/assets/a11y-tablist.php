<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * AcceDe Web accordion
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/AcceDe-Web/tablist
 */
class A11Y_Tablist implements Script {

	/** Versienummer */
	const VERSION = '2.0.1';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'a11y-tablist';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/tablist/tablist.js', [], self::VERSION, true );
	}
}
