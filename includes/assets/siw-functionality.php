<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Style;
use SIW\Traits\Assets_Handle;

/**
 * Plugin style
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Functionality implements Style {

	use Assets_Handle;

	/** {@inheritDoc */
	public function register_style() {
		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/siw.css', [], SIW_PLUGIN_VERSION );
	}
}
