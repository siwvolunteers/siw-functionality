<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor WP Rocket
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_WP_Rocket {

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {

		if ( ! class_exists( 'WP_Rocket\Plugin' ) ) {
			return;
		}
	
		$self = new self();
		add_filter( 'rocket_exclude_js', [ $self, 'set_excluded_js' ] );+
		add_filter( 'rocket_minify_excluded_external_js', [ $self, 'set_excluded_external_js' ] );
		add_filter( 'rocket_youtube_thumbnail_resolution', [ $self, 'set_youtube_thumbnail_resolution' ] );
		add_filter( 'rocket_excluded_inline_js_content', [ $self, 'set_excluded_inline_js_content' ] );
		
		/* WP Rocket White Label */
		define( 'WP_ROCKET_WHITE_LABEL_FOOTPRINT', true );
	}

	/**
	 * JS-bestanden uitsluiten van minification/concatenation
	 *
	 * @param array $excluded_files
	 * @return void
	 */
	public function set_excluded_js( $excluded_files ) {
		$excluded_files[] = '/wp-content/plugins/caldera-forms/assets/build/js/conditionals.min.js';
		$excluded_files[] = '/wp-content/plugins/wp-sentry-integration/public/(.*).js';
		return $excluded_files;
	}

	/**
	 * Sluit externe domeinen uit van minification/concatenation
	 * 
	 * - Google Analytics
	 *
	 * @param array $excluded_domains
	 * @return void
	 */
	public function set_excluded_external_js( $excluded_domains ) {
		$excluded_domains[] = 'www.google-analytics.com';
		return $excluded_domains;
	}

	/**
	 * Zet hogere resolutie van YouTube-thumbnail
	 *
	 * @param string $thumbnail_resolution
	 * @return void
	 */
	public function set_youtube_thumbnail_resolution( $thumbnail_resolution ) {
		$thumbnail_resolution = 'maxresdefault';
		return $thumbnail_resolution;
	}

	/**
	 * Sluit inline JS uit van combineren
	 * 
	 * - WP Sentry
	 * - Caldera Forms condities
	 * - Enhanced Ecommerce
	 * - Pinnacle Google Maps
	 *
	 * @param array $content
	 * @return void
	 */
	public function set_excluded_inline_js_content( $content ) {
		$content[] = 'tvc_id';
		$content[] = 'gmap3';
		$content[] = 'caldera_conditionals';
		$content[] = 'wp_sentry';
		$content[] = 'ec:';
		return $content;
	}
}