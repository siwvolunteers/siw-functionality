<?php declare(strict_types=1);

namespace SIW\Assets;

use SIW\Interfaces\Assets\External;
use SIW\Interfaces\Assets\Script;

/**
 * Facebook Pixel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://developers.facebook.com/docs/meta-pixel/get-started
 */
class Meta_Pixel implements Script {

	// TODO: External weer implementeren als er een fix is voor de volgorde

	/** Handle voor assets */
	const ASSETS_HANDLE = 'meta-pixel';

	/** {@inheritDoc} */
	public function register_script() {
		wp_register_script( self::ASSETS_HANDLE, 'https://connect.facebook.net/en_US/fbevents.js', [], null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}

	/** {@inheritDoc} */
	public function get_external_domain(): string {
		return 'connect.facebook.net';
	}
}
