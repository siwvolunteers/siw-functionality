<?php declare(strict_types=1);

namespace SIW\External_Assets;

use SIW\Config;
use SIW\Features\Cookie_Consent;

/**
 * @see       https://developers.google.com/tag-platform/tag-manager/web
 */
class Google_Tag_Manager extends External_Asset {

	#[\Override]
	protected static function get_version_number(): ?string {
		return null;
	}

	/** {@inheritDoc} */
	protected static function get_script_url(): ?string {
		return add_query_arg(
			array_filter(
				[
					'id'          => Config::get_gtm_container_id(),
					'gtm_auth'    => Config::get_gtm_auth(),
					'gtm_preview' => Config::get_gtm_preview(),
				]
			),
			'https://www.googletagmanager.com/gtm.js'
		);
	}

	#[\Override]
	protected static function get_style_url(): ?string {
		return null;
	}

	#[\Override]
	protected static function get_cookie_category(): ?string {
		return Cookie_Consent::ANALYTICAL;
	}
}
