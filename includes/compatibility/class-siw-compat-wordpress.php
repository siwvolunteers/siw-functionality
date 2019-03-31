<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor WordPRess
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 * 
 * @uses        SIW_Properties
 */
class SIW_Compat_WordPress {

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

		add_action( 'do_feed', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rdf', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss2', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_atom', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss2_comments', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_atom_comments', [ $self, 'disable_feed' ] , 1 );

		add_filter( '404_template', [ $self, 'set_404_template']);

		/* Shortcodes mogelijk maken in text widget */
		add_filter( 'widget_text', 'do_shortcode' );
		
	}

	/**
	 * Past REST-prefix aan
	 *
	 * @param string $prefix
	 * @return string
	 */
	public function set_rest_url_prefix( $prefix ) {
		$prefix = 'api';
		return $prefix;
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
		unregister_widget( 'WP_Widget_Media_Image' );
		unregister_widget( 'WP_Widget_Media_Gallery' );
		//unregister_widget( 'WP_Widget_Text' );
	}

	/**
	 * Verdubbelt levensduur nonces (i.v.m. cache)
	 *
	 * @param  int $nonce_life
	 * @return int
	 */
	public function set_nonce_life( $nonce_life ) {
		$nonce_life = 2 * DAY_IN_SECONDS;
		return $nonce_life;
	}

	/**
	 * Verwijdert auteurgegevens uit oembed
	 *
	 * @param  array $data
	 * @return array
	 */
	public function set_oembed_response_data( $data ) {
		$data['author_name'] = SIW_Properties::NAME;
		$data['author_url'] = SIW_SITE_URL;
		
		return $data;
	}

	/**
	 * Verwijdert contactmethodes bij gebruikers
	 *
	 * @param array $contactmethods
	 * @return array
	 */
	public function remove_user_contactmethods( $contactmethods ) {
		unset( $contactmethods['aim'] );
		unset( $contactmethods['jabber'] );
		unset( $contactmethods['yim'] );
		unset( $contactmethods['googleplus'] );
		unset( $contactmethods['twitter'] );
		unset( $contactmethods['facebook'] );
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
	public function remove_core_version_check_query_args( $query ) {
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
		wp_dequeue_style('wp-block-library');
	}

	/**
	 * Schakelt feed uit
	 */
	public function disable_feed() {
		wp_die( __( 'SIW heeft geen feed.', 'siw' ) );
	}

	/**
	 * Overschrijft 404-template
	 *
	 * @param string $template
	 * @return string
	 */
	public function set_404_template( $template ) {
		$template = SIW_TEMPLATES_DIR . '/404.php';
		return $template;
	}

	/**
	 * Zet alle editors standaard op tekst
	 *
	 * @param string $editor
	 */
	public function set_default_editor( $editor ) {
		$editor = 'html';
		return $editor;
	}
}
