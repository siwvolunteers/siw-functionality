<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor Safe Redirect Manager
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://wordpress.org/plugins/safe-redirect-manager/
 */
class Safe_Redirect_Manager {

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'safe-redirect-manager/safe-redirect-manager.php' ) ) {
			return;
		}
		add_filter( 'srm_default_direct_status', fn(): int => \WP_Http::MOVED_PERMANENTLY );
	}
}
