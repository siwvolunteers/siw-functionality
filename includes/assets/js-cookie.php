<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * JavaScript Cookie
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see https://github.com/js-cookie/js-cookie
 */
class JS_Cookie implements Script {

	/** Versienummer */
	const VERSION = '2.2.1';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'js-cookie';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/js-cookie/js.cookie.js', [], self::VERSION, true );
	}
}
