<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Features\Cookie_Consent;

/**
 * @see       https://developers.facebook.com/docs/meta-pixel/get-started
 */
class Meta_Pixel extends External_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return null;
	}

	#[\Override]
	protected static function get_script_url(): ?string {
		return 'https://connect.facebook.net/en_US/fbevents.js';
	}

	#[\Override]
	protected static function get_style_url(): ?string {
		return null;
	}

	#[\Override]
	protected static function get_cookie_category(): ?string {
		return Cookie_Consent::MARKETING;
	}
}
