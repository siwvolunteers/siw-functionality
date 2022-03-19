<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Properties;

/**
 * Aanpassingen voor WordPress
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
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
		add_filter( 'rest_url_prefix', fn(): string => self::REST_API_PREFIX );
		add_filter( 'user_contactmethods', '__return_empty_array', PHP_INT_MAX );
		add_action( 'init', [ $self, 'add_page_excerpt_support'] );
		add_action( 'core_version_check_query_args', [ $self, 'remove_core_version_check_query_args'] );
		add_filter( 'wp_default_editor', fn(): string => self::DEFAULT_EDITOR );
		add_filter( 'site_status_tests', [ $self, 'remove_update_check'] );
		add_filter( 'http_headers_useragent', fn(): string => Properties::NAME );
		add_filter( 'big_image_size_threshold', fn(): int => Properties::MAX_IMAGE_SIZE );

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
		add_filter( 'wp_trim_excerpt', [ $self, 'show_read_more_button' ]);

		add_filter( 'script_loader_tag', [ $self, 'set_crossorigin' ], 10, 2 );
	}

	/** Verwijdert standaard-widgets */
	public function unregister_widgets() {
		unregister_widget( \WP_Widget_Pages::class );
		unregister_widget( \WP_Widget_Recent_Posts::class );
		unregister_widget( \WP_Widget_Calendar::class );
		unregister_widget( \WP_Widget_Archives::class );
		if ( get_option( 'link_manager_enabled' ) ) {
			unregister_widget( \WP_Widget_Links::class );
		}
		unregister_widget( \WP_Widget_Meta::class );
		unregister_widget( \WP_Widget_Categories::class );
		unregister_widget( \WP_Widget_Recent_Comments::class );
		unregister_widget( \WP_Widget_RSS::class );
		unregister_widget( \WP_Widget_Tag_Cloud::class );
		unregister_widget( \WP_Widget_Custom_HTML::class );
		unregister_widget( \WP_Widget_Media_Audio::class );
		unregister_widget( \WP_Widget_Media_Video::class );
		unregister_widget( \WP_Widget_Media_Gallery::class );
	}

	/** Verwijdert auteurgegevens uit oembed */
	public function set_oembed_response_data( array $data ): array {
		$data['author_name'] = Properties::NAME;
		$data['author_url'] = SIW_SITE_URL;
		return $data;
	}

	/** Voegt samenvatting voor pagina's toe */
	public function add_page_excerpt_support() {
		add_post_type_support( 'page', 'excerpt' );
	}

	/** Verwijdert niet-essentiele gegevens voor call naar WP update server */
	public function remove_core_version_check_query_args( array $query ): array {
		unset( $query['local_package'] );
		unset( $query['blogs'] );
		unset( $query['users'] );
		unset( $query['multisite_enabled'] );
		unset( $query['initial_db_version'] );
		return $query;
	}

	/** Schakelt feed uit */
	public function disable_feed() {
		wp_redirect( home_url() );
		exit;
	}

	/** Verwijdert test voor automatische updates */
	public function remove_update_check( array $tests ): array {
		unset( $tests['async']['background_updates'] );
		return $tests;
	}

	/** Voegt toegestane css attributen toe */
	public function add_allowed_css_attributes( array $attributes ): array {
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
	public function fix_youtube_embed( string $cache ): string {
	
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
	public function manage_media_columns( array $columns ): array {
		unset( $columns['author']);
		unset( $columns['comments']);
		return $columns;
	}
	
	/** Plaats Lees meer button als gekozen is voor samenvatting */
	public function show_read_more_button( string $excerpt ): string {
	 
		// alleen bij de blog
		if ( get_post_type() !== 'post' || ! has_excerpt() ) {
			return $excerpt;
		}

		return sprintf( '%1$s <p class="read-more-button-container"><a class="button" href="%2$s">%3$s</a></p>',
			$excerpt,
			get_permalink(),
			__( 'Lees meer', 'siw' )
		);
	}

	/** Zet crossorigin attribute */
	public function set_crossorigin( string $tag, string $handle ): string {
		$crossorigin = wp_scripts()->get_data( $handle, 'crossorigin' );
		if ( $crossorigin ) {
			$tag = str_replace(
				'></',
				sprintf( ' crossorigin="%s"></', esc_attr( $crossorigin ) ),
				$tag
			);
		}
		return $tag;
	}
}
