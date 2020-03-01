<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor Safe Redirect Manager
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://wordpress.org/plugins/safe-redirect-manager/
 * @since     3.0.0
 */
class Safe_Redirect_Manager {

	/**
	 * Maximaal aantal redirects
	 * 
	 * @var int
	 */
	const MAX_REDIRECTS = 250;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_filter( 'srm_max_redirects', [ $self, 'set_max_redirects'] );
		add_filter( 'srm_default_direct_status', [ $self, 'set_default_direct_status'] );
	}

	/**
	 * Past maximaal aantal redirects aan
	 *
	 * @return int
	 */
	public function set_max_redirects() {
		return self::MAX_REDIRECTS;
	}

	/**
	 * Past standaard redirect statuscode aan
	 *
	 * @return int
	 */
	public function set_default_direct_status() {
		return \WP_Http::MOVED_PERMANENTLY;
	}
}
