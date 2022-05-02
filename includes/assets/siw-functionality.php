<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Style;

/**
 * Plugin style
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Functionality implements Style {

	/** Handle voor assets */
	const ASSETS_HANDLE = 'siw-functionality';

	/** {@inheritDoc */
	public function register_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/siw.css', [ Balloon::ASSETS_HANDLE ], SIW_PLUGIN_VERSION );
	}
}
