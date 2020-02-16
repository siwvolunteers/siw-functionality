<?php

namespace SIW\Compatibility;

/**
 * Aanpassingen voor WP Rocket
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://wp-rocket.me/
 * @since     3.0.0
 */
class WP_Rocket {

	/**
	 * Resolutie van YouTube-thumbnail
	 * 
	 * @var string
	 */
	const YOUTUBE_THUMBNAIL_RESOLUTION = 'maxresdefault';

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( '\WP_Rocket\Plugin' ) ) {
			return;
		}
	
		$self = new self();

		add_action( 'siw_update_plugin', [ $self, 'purge_cache' ] );
		add_filter( 'rocket_lazyload_youtube_thumbnail_resolution', [ $self, 'set_youtube_thumbnail_resolution' ] );
		define( 'WP_ROCKET_WHITE_LABEL_FOOTPRINT', true );
	}

	/**
	 * Cache legen na update plugin
	 */
	public function purge_cache() {
		rocket_clean_domain();
		rocket_clean_minify();
		rocket_clean_cache_busting();
	}

	/**
	 * Zet hogere resolutie van YouTube-thumbnail
	 *
	 * @return string
	 */
	public function set_youtube_thumbnail_resolution() {
		return self::YOUTUBE_THUMBNAIL_RESOLUTION;
	}
}
