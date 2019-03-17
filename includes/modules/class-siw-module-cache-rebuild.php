<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Verversen van de cache
 * 
 * @package   SIW\Modules
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Util
 * @uses      SIW_Properties
 */

class SIW_Module_Cache_Rebuild {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! defined( 'WP_ROCKET_VERSION' ) ) {
			return false;
		}
		$self = new self();
		add_action( 'siw_update_plugin', [ $self, 'schedule_cache_rebuild' ] );
		add_action( 'siw_rebuild_cache', [ $self, 'rebuild_cache' ] );
		add_action( 'before_run_rocket_sitemap_preload', [ $self, 'setup_sitemap' ], 10, 2 );
		add_filter( 'rocket_sitemap_preload_list', [ $self, 'set_sitemaps_for_preload' ] );
	}

	/**
	 * Voegt een scheduled event toe
	 */
	public function schedule_cache_rebuild() {
		/* Cache rebuild schedulen */
		$cache_rebuild_ts = strtotime( 'tomorrow ' . SIW_Properties::TS_CACHE_REBUILD );
		$cache_rebuild_ts_gmt = SIW_Util::convert_timestamp_to_gmt( $cache_rebuild_ts );
		if ( wp_next_scheduled( 'siw_rebuild_cache' ) ) {
			$timestamp = wp_next_scheduled( 'siw_rebuild_cache' );
			wp_unschedule_event( $timestamp, 'siw_rebuild_cache' );
		}
		wp_schedule_event( $cache_rebuild_ts_gmt, 'daily', 'siw_rebuild_cache' );
	}

	/**
	 * Leegt de cache en start de preload
	 */
	public function rebuild_cache() {
		rocket_clean_domain();
		run_rocket_sitemap_preload();
	}

	/**
	 * Genereert de sitemap voor de preload
	 *
	 * @param string $sitemap_type
	 * @param string $sitemap_url
	 */
	public function setup_sitemap( $sitemap_type, $sitemap_url ) {
		if ( ! function_exists( 'the_seo_framework' ) ) {
			return;
		} 
		$tsf = the_seo_framework();
		if ( $sitemap_url == $tsf->get_sitemap_xml_url() ) {
			$tsf->setup_sitemap();
		}
	}

	/**
	 * Voegt alle sitemaps toe aan preload
	 *
	 * @param array $sitemaps
	 */
	public function set_sitemaps_for_preload( $sitemaps ) {
		if ( ! function_exists( 'the_seo_framework' ) ) {
			return $sitemaps;
		} 
		if ( get_rocket_option( 'tsf_xml_sitemap', false ) ) {
			$tsf = the_seo_framework();
			$languages = SIW_i18n::get_active_languages();
			$sitemap_url = $tsf->get_sitemap_xml_url();
			foreach ( $languages as $code => $language ) {
				$sitemaps[] = SIW_i18n::get_translated_permalink( $sitemap_url, $language['code'] );
			}
		}
		return $sitemaps;
	}
}