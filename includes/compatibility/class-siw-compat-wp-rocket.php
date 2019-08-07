<?php

/**
 * Aanpassingen voor WP Rocket
 * 
 * @package     SIW\Compatibility
 * @copyright   2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Compat_WP_Rocket {

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( 'WP_Rocket\Plugin' ) ) {
			return;
		}
	
		$self = new self();
		add_filter( 'rocket_exclude_js', [ $self, 'set_excluded_js' ] );
		add_filter( 'rocket_minify_excluded_external_js', [ $self, 'set_excluded_external_js' ] );
		add_filter( 'rocket_lazyload_youtube_thumbnail_resolution', [ $self, 'set_youtube_thumbnail_resolution' ] );
		add_filter( 'rocket_excluded_inline_js_content', [ $self, 'set_excluded_inline_js_content' ] );
		add_action( 'wp_rocket_loaded', [ $self, 'remove_all_purge_hooks' ] );
	
		define( 'WP_ROCKET_WHITE_LABEL_FOOTPRINT', true );
	}

	/**
	 * JS-bestanden uitsluiten van minification/concatenation
	 *
	 * @param array $excluded_files
	 * @return array
	 */
	public function set_excluded_js( array $excluded_files ) {
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
	 * @return array
	 */
	public function set_excluded_external_js( array $excluded_domains ) {
		$excluded_domains[] = 'www.google-analytics.com';
		return $excluded_domains;
	}

	/**
	 * Zet hogere resolutie van YouTube-thumbnail
	 *
	 * @param string $thumbnail_resolution
	 * @return string
	 */
	public function set_youtube_thumbnail_resolution( string $thumbnail_resolution ) {
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
	 * @return array
	 */
	public function set_excluded_inline_js_content( array $content ) {
		$content[] = 'tvc_id';
		$content[] = 'gmap3';
		$content[] = 'caldera_conditionals';
		$content[] = 'wp_sentry';
		$content[] = 'ec:';
		return $content;
	}

	/**
	 * Onderdrukken van automatische cache-purge
	 * 
	 * @see https://docs.wp-rocket.me/article/137-disable-all-automatic-cache-clearing
	 */
	public function remove_all_purge_hooks() {
		
		$clean_domain_hooks = [
			'switch_theme',
			'user_register',
			'profile_update',
			'deleted_user',
			'wp_update_nav_menu',
			'update_option_theme_mods_' . get_option( 'stylesheet' ),
			'update_option_sidebars_widgets',
			'update_option_category_base',
			'update_option_tag_base',
			'permalink_structure_changed',
			'create_term',
			'edited_terms',
			'delete_term',
			'add_link',
			'edit_link',
			'delete_link',
			'customize_save',
			'avada_clear_dynamic_css_cache',
		];

		foreach ( $clean_domain_hooks as $handle ) {
			remove_action( $handle, 'rocket_clean_domain' );
		}

		$clean_post_hooks = [
			'wp_trash_post',
			'delete_post',
			'clean_post_cache',
			'wp_update_comment_count',
		];
		
		foreach ( $clean_post_hooks as $handle ) {
			remove_action( $handle, 'rocket_clean_post' );
		}
			
		remove_filter( 'widget_update_callback'	, 'rocket_widget_update_callback' );
		remove_action( 'upgrader_process_complete', 'rocket_clean_cache_theme_update', 10, 2 ); 
	}

}
