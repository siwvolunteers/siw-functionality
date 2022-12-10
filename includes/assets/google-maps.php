<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;

/**
 * Google Maps JS API
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://developers.google.com/maps/documentation/javascript/tutorial
 */
class Google_Maps implements Script, External {

	/** Handle voor assets */
	const ASSETS_HANDLE = 'google-maps';

	/** URL voor Google Maps API */
	const API_URL = 'https://maps.googleapis.com/maps/api/js';

	/**
	 * Versie
	 * - `weekly`
	 * - `quarterly`
	 * - `beta`
	 * - `n.nn` (specifieke versie)
	 *
	 * @see https://developers.google.com/maps/documentation/javascript/versions#release-channels-and-version-numbers
	*/
	const VERSION = 'weekly';

	/** {@inheritDoc} */
	public function register_script() {
		$google_maps_url = add_query_arg(
			[
				'key' => siw_get_option( 'google_maps.api_key', '' ),
				'v'   => self::VERSION,
			],
			self::API_URL
		);
		wp_register_script( self::ASSETS_HANDLE, $google_maps_url, [], null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}

	/** {@inheritDoc} */
	public function get_external_domain(): string {
		return 'maps.googleapis.com';
	}
}
