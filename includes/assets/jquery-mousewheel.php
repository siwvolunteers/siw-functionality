<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * Jquery mousewheel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://github.com/jquery/jquery-mousewheel
 */
class JQuery_Mousewheel implements Script {

	/** Versienummer */
	const VERSION = '3.1.13';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'jquery-mousewheel';

	/** Registreert script */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/mapplic/js/jquery.mousewheel.js', [ 'jquery' ], self::VERSION, true );
	}
}
