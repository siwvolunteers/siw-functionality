<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;

/**
 * Google Analytics JS API
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @see       https://developers.google.com/analytics/devguides/collection/analyticsjs
 */
class Google_Analytics implements Script, External{
	
	/** Handle voor assets */
	const ASSETS_HANDLE = 'google-analytics';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, 'https://www.google-analytics.com/analytics.js', [], null, true );
	}

	/** {@inheritDoc} */
	public function get_external_domain(): string {
		return 'www.google-analytics.com';
	}
}
