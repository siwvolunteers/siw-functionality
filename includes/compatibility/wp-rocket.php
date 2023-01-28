<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Update;

/**
 * Aanpassingen voor WP Rocket
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://wp-rocket.me/
 */
class WP_Rocket extends Plugin {

	#[Filter( 'rocket_lazyload_youtube_thumbnail_resolution' )]
	/** Resolutie van YouTube-thumbnail */
	private const YOUTUBE_THUMBNAIL_RESOLUTION = 'maxresdefault';

	#[Filter( 'nonce_life' )]
	/** Levensduur van nonce in seconden */
	private const NONCE_LIFESPAN = 2 * DAY_IN_SECONDS;

	/** Tijdstip cache legen */
	private const TS_CACHE_CLEAR = '05:00';

	/** Hooknaam */
	private const CACHE_CLEAR_HOOK = 'siw_rebuild_cache';

	/** {@inheritDoc} */
	protected static function get_plugin_path(): string {
		return 'wp-rocket/wp-rocket.php';
	}

	#[Action( Update::PLUGIN_UPDATED_HOOK )]
	#[Action( self::CACHE_CLEAR_HOOK )]
	/** Cache legen */
	public function clear_cache() {
		rocket_clean_domain();
		rocket_clean_minify();
		rocket_clean_cache_busting();
	}

	#[Action( Update::PLUGIN_UPDATED_HOOK )]
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
