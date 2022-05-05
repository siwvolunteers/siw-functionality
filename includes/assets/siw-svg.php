<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * SVG functies
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class SIW_SVG implements Script {

	/** Handle voor assets */
	const ASSETS_HANDLE = 'siw-svg';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/siw-svg.js', [], SIW_PLUGIN_VERSION, true );
	}
}
