<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Config;
use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;

/**
 * Google Analytics 4 JS API
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://developers.google.com/analytics/devguides/collection/ga4
 */
class Google_Analytics_4 implements Script, External {

	/** Handle voor assets */
	const ASSETS_HANDLE = 'google-analytics-4';

	/** {@inheritDoc} */
	public function register_script() {

		$url = add_query_arg(
			[
				'id' => Config::get_google_analytics_measurement_id(),
			],
			'https://www.googletagmanager.com/gtag/js'
		);
		wp_register_script( self::ASSETS_HANDLE, $url, [], null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}

	/** {@inheritDoc} */
	public function get_external_domain(): string {
		return 'www.googletagmanager.com';
	}
}
