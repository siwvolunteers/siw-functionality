<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Properties;

/**
 * Aanpassingen voor WordPress
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class WordPress {

	/** URL-prefix voor WP REST API */
	const REST_API_PREFIX = 'api';

	/** Default editor mode */
	const DEFAULT_EDITOR ='html';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'widgets_init', [ $self, 'unregister_widgets'], 99 );
		add_filter( 'oembed_response_data', [ $self, 'set_oembed_response_data' ] );
		add_filter( 'rest_url_prefix', fn() : string => self::REST_API_PREFIX );
		add_filter( 'user_contactmethods', '__return_empty_array', PHP_INT_MAX );
		add_action( 'init', [ $self, 'add_page_excerpt_support'] );
		add_action( 'core_version_check_query_args', [ $self, 'remove_core_version_check_query_args'] );
		add_action( 'wp_enqueue_scripts', [ $self, 'dequeue_styles' ], PHP_INT_MAX );
		add_filter( 'wp_default_editor', fn() : string => self::DEFAULT_EDITOR );
		add_filter( 'site_status_tests', [ $self, 'remove_update_check'] );
		add_filter( 'http_headers_useragent', fn() : string => Properties::NAME );
		add_filter( 'big_image_size_threshold', fn() : int => Properties::MAX_IMAGE_SIZE );

		add_filter( 'wp_is_application_passwords_available', '__return_false' );
		add_filter( 'comments_open', '__return_false' );

		add_action( 'do_feed', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rdf', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss2', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_atom', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_rss2_comments', [ $self, 'disable_feed' ] , 1 );
		add_action( 'do_feed_atom_comments', [ $self, 'disable_feed' ] , 1 );

		add_filter( 'widget_text', 'do_shortcode' );

		add_filter( 'safe_style_css', [ $self, 'add_allowed_css_attributes' ] );
		add_filter( 'embed_oembed_html', [ $self, 'fix_youtube_embed' ] );

		//Attachments
		add_filter( 'disable_months_dropdown', '__return_true' );
		add_filter( 'manage_media_columns', [ $self, 'manage_media_columns'], 10, 2 );
		add_filter( 'wp_trim_excerpt', [$self,'excerpt_metabox_more' ]);	// lees meer knop voor berichten
	}

	/** Verwijdert standaard-widgets */
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

	/** Verwijdert auteurgegevens uit oembed */
	public function set_oembed_response_data( array $data ) : array {
		$data['author_name'] = Properties::NAME;
		$data['author_url'] = SIW_SITE_URL;
		return $data;
	}

	/** Voegt samenvatting voor pagina's toe */
	public function add_page_excerpt_support() {
		add_post_type_support( 'page', 'excerpt' );
	}

	/** Verwijdert niet-essentiele gegevens voor call naar WP update server */
	public function remove_core_version_check_query_args( array $query ) : array {
		unset( $query['local_package'] );
		unset( $query['blogs'] );
		unset( $query['users'] );
		unset( $query['multisite_enabled'] );
		unset( $query['initial_db_version'] );
		return $query;
	}

	/** Gutenberg css uitschakelen */
	public function dequeue_styles() {
		wp_dequeue_style( 'wp-block-library' );
	}

	/** Schakelt feed uit */
	public function disable_feed() {
		wp_redirect( home_url() );
		exit;
	}

	/** Verwijdert test voor automatische updates */
	public function remove_update_check( array $tests ) : array {
		unset( $tests['async']['background_updates'] );
		return $tests;
	}

	/** Voegt toegestane css attributen toe */
	public function add_allowed_css_attributes( array $attributes ) : array {
		$attributes[] = 'fill';
		$attributes[] = 'opacity';
		$attributes[] = 'transform';
		$attributes[] = 'content';
		return $attributes;
	}

	/**
	 * Past YouTube-embed link aan
	 * - nocookie domein
	 * - instellingen
	 */
	public function fix_youtube_embed( string $cache ) : string {
	
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

	/** Verberg admin columns bij attachments */
	public function manage_media_columns( array $columns, bool $detached ) : array {
		unset( $columns['author']);
		unset( $columns['comments']);
		return $columns;
	}
	/** Plaats Lees meer button als gekozen is voor samenvatting */
	function excerpt_metabox_more( $excerpt ) {
        $output = $excerpt;
		$post = get_post_type();
		if($post != "post") { return($output);} // alleen bij de blog
        if ( has_excerpt()) {
            $output = sprintf( '%1$s <p class="read-more-button-container"><a class="button" href="%2$s">%3$s</a></p>',
            $excerpt,
            get_permalink(),
            __( 'Lees meer', 'siw' ));
        }
        return $output;
    }
}
