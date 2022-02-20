<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * Micromodal
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://micromodal.now.sh/
 */
class Micromodal implements Script {

	/** Versienummer */
	const VERSION = '0.4.10';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'micromodal';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/micromodal/micromodal.js', [], self::VERSION, true );
	}
}
