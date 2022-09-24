<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Style as I_Style;

/**
 * Validatiemeldingen in NL voor jQuery validation
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see https://github.com/jquery-validation/jquery-validation/blob/master/src/localization/messages_nl.js
 */
class JQuery_Validation_Messages_NL implements I_Style {

	/** Versienummer */
	const VERSION = SIW_PLUGIN_VERSION;

	/** Handle voor assets */
	const ASSETS_HANDLE = 'jquery-validation-messages-nl';

	/** {@inheritDoc} */
	public function register_style() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/jquery-validation-messages-nl.js', [ 'jquery' ], self::VERSION, true );
	}
}
