<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\i18n;

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
	 * Levensduur van nonce in seconden
	 * 
	 * @var int
	 */
	const NONCE_LIFESPAN = 2 * DAY_IN_SECONDS;

	/**
	 * Tijdstip cache opnieuw opbouwen
	 *
	 * @var string
	 */
	const TS_CACHE_REBUILD = '05:00';

	/**
	 * Hooknaam
	 * 
	 * @var string
	 */
	const HOOK = 'siw_rebuild_cache';

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( '\WP_Rocket\Plugin' ) ) {
			return;
		}
		$self = new self();

		add_action( 'siw_update_plugin', [ $self, 'purge_cache' ] );
		add_filter( 'rocket_lazyload_youtube_thumbnail_resolution', fn() : string => self::YOUTUBE_THUMBNAIL_RESOLUTION );
		define( 'WP_ROCKET_WHITE_LABEL_FOOTPRINT', true );
		add_filter( 'nonce_life', fn() : int => self::NONCE_LIFESPAN );

		//Acties t.b.v. cache rebuild
		add_action( 'siw_update_plugin', [ $self, 'schedule_cache_rebuild' ] );
		add_action( self::HOOK, [ $self, 'rebuild_cache' ] );
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
	* Voegt een scheduled event toe
	*/
	public function schedule_cache_rebuild() {
		/* Cache rebuild schedulen */
		$cache_rebuild_ts = strtotime( 'tomorrow ' . self::TS_CACHE_REBUILD . wp_timezone_string() );
		if ( wp_next_scheduled( self::HOOK ) ) {
			wp_clear_scheduled_hook( self::HOOK );
		}
		wp_schedule_event( $cache_rebuild_ts, 'daily', self::HOOK );
	}

	/**
	 * Leegt de cache en start de preload
	 */
	public function rebuild_cache() {
		$this->purge_cache();
		run_rocket_sitemap_preload();
	}
}
