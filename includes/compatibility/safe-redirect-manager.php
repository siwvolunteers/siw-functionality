<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor Safe Redirect Manager
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://wordpress.org/plugins/safe-redirect-manager/
 * @since     3.0.0
 */
class Safe_Redirect_Manager {

	/** Maximaal aantal redirects */
	const MAX_REDIRECTS = 250;

	/** Init */
	public static function init() {
		add_filter( 'srm_max_redirects', fn() : int => self::MAX_REDIRECTS );
		add_filter( 'srm_default_direct_status', fn() : int => \WP_Http::MOVED_PERMANENTLY );
	}
}
