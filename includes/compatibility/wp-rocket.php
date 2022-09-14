<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Update;

/**
 * Aanpassingen voor WP Rocket
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://wp-rocket.me/
 */
class WP_Rocket {

	/** Resolutie van YouTube-thumbnail */
	const YOUTUBE_THUMBNAIL_RESOLUTION = 'maxresdefault';

	/** Levensduur van nonce in seconden */
	const NONCE_LIFESPAN = 2 * DAY_IN_SECONDS;

	/** Tijdstip cache legen */
	const TS_CACHE_CLEAR = '05:00';

	/** Hooknaam */
	const CACHE_CLEAR_HOOK = 'siw_rebuild_cache';

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'wp-rocket/wp-rocket.php' ) ) {
			return;
		}
		$self = new self();

		add_action( Update::PLUGIN_UPDATED_HOOK, [ $self, 'clear_cache' ] );
		add_filter( 'rocket_lazyload_youtube_thumbnail_resolution', fn() : string => self::YOUTUBE_THUMBNAIL_RESOLUTION );
		define( 'WP_ROCKET_WHITE_LABEL_FOOTPRINT', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		add_filter( 'nonce_life', fn() : int => self::NONCE_LIFESPAN );

		// Acties t.b.v. cache rebuild
		add_action( Update::PLUGIN_UPDATED_HOOK, [ $self, 'schedule_cache_clear' ] );
		add_action( self::CACHE_CLEAR_HOOK, [ $self, 'clear_cache' ] );
	}

	/** Cache legen */
	public function clear_cache() {
		rocket_clean_domain();
		rocket_clean_minify();
		rocket_clean_cache_busting();
	}

	/** Voegt een scheduled event toe */
	public function schedule_cache_clear() {
		/* Cache rebuild schedulen */
		$cache_rebuild_ts = strtotime( 'tomorrow ' . self::TS_CACHE_CLEAR . wp_timezone_string() );
		if ( wp_next_scheduled( self::CACHE_CLEAR_HOOK ) ) {
			wp_clear_scheduled_hook( self::CACHE_CLEAR_HOOK );
		}
		wp_schedule_event( $cache_rebuild_ts, 'daily', self::CACHE_CLEAR_HOOK );
	}

}
