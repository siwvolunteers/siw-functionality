<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\Script;

/**
 * Frappe Charts
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see https://frappe.io/charts
 */
class Frappe_Charts implements Script {

	/** Versienummer */
	const VERSION = '1.6.2';

	/** Handle voor assets */
	const ASSETS_HANDLE = 'frappe-charts';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'vendor/frappe-charts/frappe-charts.min.umd.js', [ Polyfill::ASSETS_HANDLE ], self::VERSION, true );
	}
}
