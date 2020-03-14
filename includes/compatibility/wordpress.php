<?php

namespace SIW\Compatibility;

use SIW\Properties;

/**
 * Aanpassingen voor WordPress
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class WordPress {

	/**
	 * URL-prefix voor WP REST API
	 * 
	 * @var string
	 */
	const REST_API_PREFIX = 'api';

	/**
	 * Default editor mode
	 * 
	 * @var string
	 */
	const DEFAULT_EDITOR ='html';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'widgets_init', [ $self, 'unregister_widgets'], 99 );
		add_filter( 'nonce_life', [ $self, 'set_nonce_life' ] );
		add_filter( 'oembed_response_data', [ $self, 'set_oembed_response_data' ] );
		add_filter( 'rest_url_prefix', [ $self, 'set_rest_url_prefix' ] );
		add_filter( 'user_contactmethods', [ $self, 'remove_user_contactmethods' ], PHP_INT_MAX );
		add_action( 'after_setup_theme', [ $self, 'add_custom_logo_support'] );
		add_action( 'init', [ $self, 'add_page_excerpt_support'] );
		add_action( 'core_version_check_query_args', [ $self, 'remove_core_version_check_query_args'] );
		add_action( 'wp_enqueue_scripts', [ $self, 'dequeue_styles' ], PHP_INT_MAX );
		add_filter( 'wp_default_editor', [ $self, 'set_default_editor'] );
		add_filter( 'site_status_tests', [ $self, 'remove_update_check'] );
		add_filter( 'http_headers_useragent', [ $self, 'set_http_headers_useragent'] );
		add_filter( 'big_image_size_threshold', [ $self, 'set_big_image_size_threshold'] );


		add_action( 'do_feed', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rdf', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss2', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_atom', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss2_comments', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_atom_comments', [ $self, 'disable_feed' ] , 1 );

		add_filter( '404_template', [ $self, 'set_404_template']);

		add_filter( 'widget_text', 'do_shortcode' );

		add_filter( 'safe_style_css', [ $self, 'add_allowed_css_attributes' ] );
		add_filter( 'embed_oembed_html', [ $self, 'fix_youtube_embed' ] );
	}

	/**
	 * Past REST-prefix aan
	 *
	 * @return string
	 */
	public function set_rest_url_prefix() {
		return self::REST_API_PREFIX;
	}

	/**
	 * Verwijdert standaard-widgets
	 */
	public function unregister_widgets() {
		unregister_widget( 'WP_Widget_Pages' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
		unregister_widget( 'WP_Widget_Calendar' );
		unregister_widget( 'WP_Widget_Archives' );
		if ( get_option( 'link_manager_enabled' ) ) {
			unregister_widget( 'WP_Widget_Links' );
		}
		unregister_widget( 'WP_Widget_Meta' );
		unregister_widget( 'WP_Widget_Categories' );
		unregister_widget( 'WP_Widget_Recent_Comments' );
		unregister_widget( 'WP_Widget_RSS' );
		unregister_widget( 'WP_Widget_Tag_Cloud' );
		unregister_widget( 'WP_Widget_Custom_HTML' );
		unregister_widget( 'WP_Widget_Media_Audio' );
		unregister_widget( 'WP_Widget_Media_Video' );
		unregister_widget( 'WP_Widget_Media_Gallery' );
	}

	/**
	 * Verdubbelt levensduur nonces (i.v.m. cache)
	 *
	 * @return int
	 */
	public function set_nonce_life() {
		return 2 * DAY_IN_SECONDS;
	}

	/**
	 * Verwijdert auteurgegevens uit oembed
	 *
	 * @param  array $data
	 * 
	 * @return array
	 */
	public function set_oembed_response_data( array $data ) {
		$data['author_name'] = Properties::NAME;
		$data['author_url'] = SIW_SITE_URL;
		
		return $data;
	}

	/**
	 * Verwijdert contactmethodes bij gebruikers
	 *
	 * @param array $contactmethods
	 * 
	 * @return array
	 */
	public function remove_user_contactmethods( array $contactmethods ) {
		unset( $contactmethods['aim'] );
		unset( $contactmethods['jabber'] );
		unset( $contactmethods['yim'] );

		return $contactmethods;
	}

	/**
	 * Voegt support voor custom logo toe
	 */
	public function add_custom_logo_support() {
		add_theme_support( 'custom-logo' );
	}

	/**
	 * Voegt samenvatting voor pagina's toe
	 */
	public function add_page_excerpt_support() {
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Verwijdert niet-essentiele gegevens voor call naar WP update server
	 *
	 * @param array $query
	 * @return array
	 */
	public function remove_core_version_check_query_args( array $query ) {
		unset( $query['local_package'] );
		unset( $query['blogs'] );
		unset( $query['users'] );
		unset( $query['multisite_enabled'] );
		unset( $query['initial_db_version'] );
		return $query;
	}

	/**
	 * Gutenberg css uitschakelen
	 */
	public function dequeue_styles() {
		wp_dequeue_style( 'wp-block-library' );
	}

	/**
	 * Schakelt feed uit
	 */
	public function disable_feed() {
		wp_redirect( home_url() );
		exit;
	}

	/**
	 * Overschrijft 404-template
	 *
	 * @return string
	 */
	public function set_404_template() {
		return SIW_TEMPLATES_DIR . '/404.php';
	}

	/**
	 * Zet alle editors standaard op tekst
	 *
	 * @return string
	 */
	public function set_default_editor() {
		return self::DEFAULT_EDITOR;
	}

	/**
	 * Zet useragent voor alle uitgaande http requests
	 * 
	 * @return string
	 */
	public function set_http_headers_useragent() {
		return Properties::NAME;
	}

	/**
	 * Zet grens voor grote afbeelding
	 *
	 * @return int
	 */
	public function set_big_image_size_threshold() {
		return Properties::MAX_IMAGE_SIZE;;
	}

	/**
	 * Verwijdert test voor automatische updates
	 *
	 * @param array $tests
	 * 
	 * @return array
	 */
	public function remove_update_check( array $tests ) {
		unset( $tests['async']['background_updates'] );
		return $tests;
	}

	/**
	 * Voegt toegestane css attributen toe
	 *
	 * @param array $attributes
	 *
	 * @return array
	 */
	public function add_allowed_css_attributes( array $attributes ) {
		$attributes[] = 'fill';
		$attributes[] = 'opacity';
		$attributes[] = 'transform';
		$attributes[] = 'content';
		return $attributes;
	}

	/**
	 * Past YouTube-embed link aan
	 * 
	 * - nocookie domein
	 * - instellingen
	 *
	 * @param string $cache
	 *
	 * @return string
	 */
	public function fix_youtube_embed( string $cache ) {
	
		$regex = '/<iframe[^>]*(?<=src=")(https:\/\/www\.youtube\.com\/embed.*?)(?=[\"])/m';

		preg_match( $regex, $cache, $matches );
		if ( ! isset( $matches[1] ) ) {
			return $cache;
		}
		
		$url_parts = parse_url( $matches[1] );
		$url = $url_parts['scheme'] . '://' . 'www.youtube-nocookie.com' . $url_parts['path'];
		$url = add_query_arg( [ 
			'rel'            => false,
			'modestbranding' => true,
			'controls'       => true,
			'fs'             => false,
		], $url );

		return str_replace( $matches[1], $url, $cache );
	}
}
