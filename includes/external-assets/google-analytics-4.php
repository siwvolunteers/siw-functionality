<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Config;

/**
 * Google Analytics 4 JS API
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://developers.google.com/analytics/devguides/collection/ga4
 */
class Google_Analytics_4 extends External_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_url(): ?string {

		return add_query_arg(
			[
				'id' => Config::get_google_analytics_measurement_id(),
			],
			'https://www.googletagmanager.com/gtag/js'
		);
	}

	/** {@inheritDoc} */
	protected static function get_style_url(): ?string {
		return null;
	}

}
