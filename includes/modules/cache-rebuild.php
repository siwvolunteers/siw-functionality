<?php

namespace SIW\Modules;

use SIW\i18n;
use SIW\Util;

/**
 * Verversen van de cache
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo      verplaatsen naar Compat/WP-Rocket ?
 */
class Cache_Rebuild {

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
		if ( ! defined( 'WP_ROCKET_VERSION' ) ) {
			return false;
		}
		$self = new self();
		add_action( 'siw_update_plugin', [ $self, 'schedule_cache_rebuild' ] );
		add_action( self::HOOK, [ $self, 'rebuild_cache' ] );
		add_filter( 'rocket_sitemap_preload_list', [ $self, 'set_sitemaps_for_preload' ] );
	}

	/**
	 * Voegt een scheduled event toe
	 */
	public function schedule_cache_rebuild() {
		/* Cache rebuild schedulen */
		$cache_rebuild_ts = strtotime( 'tomorrow ' . self::TS_CACHE_REBUILD );
		$cache_rebuild_ts_gmt = Util::convert_timestamp_to_gmt( $cache_rebuild_ts );
		if ( wp_next_scheduled( self::HOOK ) ) {
			wp_clear_scheduled_hook( self::HOOK );
		}
		wp_schedule_event( $cache_rebuild_ts_gmt, 'daily', self::HOOK );
	}

	/**
	 * Leegt de cache en start de preload
	 */
	public function rebuild_cache() {
		rocket_clean_domain();
		rocket_clean_minify();
		rocket_clean_cache_busting();
		run_rocket_sitemap_preload();
	}

	/**
	 * Voegt alle sitemaps toe aan preload
	 *
	 * @param array $sitemaps
	 */
	public function set_sitemaps_for_preload( $sitemaps ) {
		if ( ! class_exists( '\The_SEO_Framework\Bridges\Sitemap' ) ) {
			return $sitemaps;
		} 
		if ( get_rocket_option( 'tsf_xml_sitemap', false ) ) {
			$languages = i18n::get_active_languages();
			$sitemap_url = \The_SEO_Framework\Bridges\Sitemap::get_instance()->get_expected_sitemap_endpoint_url();
			foreach ( $languages as $language ) {
				$sitemaps[] = i18n::get_translated_permalink( $sitemap_url, $language['code'] );
			}
		}
		return $sitemaps;
	}
}
