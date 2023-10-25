<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;
use SIW\Traits\Assets_Handle;

/**
 * SVG functies
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class SIW_SVG implements Script {

	use Assets_Handle;

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::get_assets_handle(), SIW_ASSETS_URL . 'js/siw-svg.js', [], SIW_PLUGIN_VERSION, true );
	}
}
