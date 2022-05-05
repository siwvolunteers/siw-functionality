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

	/** Tijdstip cache opnieuw opbouwen */
	const TS_CACHE_REBUILD = '05:00';

	/** Hooknaam */
	const HOOK = 'siw_rebuild_cache';

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'wp-rocket/wp-rocket.php' ) ) {
			return;
		}
		$self = new self();

		add_action( Update::PLUGIN_UPDATED_HOOK, [ $self, 'purge_cache' ] );
		add_filter( 'rocket_lazyload_youtube_thumbnail_resolution', fn() : string => self::YOUTUBE_THUMBNAIL_RESOLUTION );
		define( 'WP_ROCKET_WHITE_LABEL_FOOTPRINT', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		add_filter( 'nonce_life', fn() : int => self::NONCE_LIFESPAN );

		// Acties t.b.v. cache rebuild
		add_action( Update::PLUGIN_UPDATED_HOOK, [ $self, 'schedule_cache_rebuild' ] );
		add_action( self::HOOK, [ $self, 'rebuild_cache' ] );
	}

	/** Cache legen na update plugin */
	public function purge_cache() {
		rocket_clean_domain();
		rocket_clean_minify();
		rocket_clean_cache_busting();
	}

	/** Voegt een scheduled event toe */
	public function schedule_cache_rebuild() {
		/* Cache rebuild schedulen */
		$cache_rebuild_ts = strtotime( 'tomorrow ' . self::TS_CACHE_REBUILD . wp_timezone_string() );
		if ( wp_next_scheduled( self::HOOK ) ) {
			wp_clear_scheduled_hook( self::HOOK );
		}
		wp_schedule_event( $cache_rebuild_ts, 'daily', self::HOOK );
	}

	/** Leegt de cache en start de preload */
	public function rebuild_cache() {
		$this->purge_cache();
		run_rocket_sitemap_preload();
	}
}
