<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Config;

/**
 * Facebook Pixel
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 *
 * @see       https://developers.facebook.com/docs/meta-pixel/get-started
 */
class Meta_Pixel extends External_Asset {

	/** {@inheritDoc} */
	protected static function get_version_number(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_url(): ?string {

		return 'https://connect.facebook.net/en_US/fbevents.js';
	}

	/** {@inheritDoc} */
	protected static function get_style_url(): ?string {
		return null;
	}
}
