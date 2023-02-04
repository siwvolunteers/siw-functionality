<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Properties;
use SIW\Update;
use SIW\Util\CSS;

/**
 * Aanpassingen voor WordPress
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class WordPress extends Base {

	#[Filter( 'rest_url_prefix' )]
	/** URL-prefix voor WP REST API */
	private const REST_API_PREFIX = 'api';

	#[Filter( 'wp_default_editor' )]
	/** Default editor mode */
	private const DEFAULT_EDITOR = 'html';

	#[Filter( 'big_image_size_threshold' )]
	private const BIG_IMAGE_SIZE_THRESHOLD = Properties::MAX_IMAGE_SIZE;

	#[Filter( 'wp_is_application_passwords_available' )]
	private const APPLICATION_PASSWORDS_AVAILABLE = false;

	#[Filter( 'comments_open' )]
	private const COMMENTS_OPEN = false;

	#[Filter( 'disable_months_dropdown' )]
	private const DISABLE_MONTHS_DROPDOWN = true;

	#[Filter( 'user_contactmethods' )]
	private const USER_CONTACT_METHODS = [];

	#[Filter( 'admin_email_check_interval' )]
	private const ADMIN_EMAIL_CHECK_INTERVAL = 0;

	#[Action( Update::PLUGIN_UPDATED_HOOK )]
	/** Flusht rewrite rules na plugin update */
	public function flush_rewrite_rules() {
		\flush_rewrite_rules();
	}

	#[Action( 'wp_head', 1 )]
	public function cleanup_head() {
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	}

	#[Filter( 'site_icon_meta_tags' )]
	public function add_theme_color_tag( array $meta_tags ): array {
		$meta_tags[] = sprintf( '<meta name="theme-color" content="%s">', CSS::ACCENT_COLOR );
		return $meta_tags;
	}

	#[Action( 'widgets_init', 99 )]
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

	#[Action( 'oembed_response_data' )]
	/** Verwijdert auteurgegevens uit oembed */
	public function set_oembed_response_data( array $data ): array {
		$data['author_name'] = Properties::NAME;
		$data['author_url'] = SIW_SITE_URL;
		return $data;
	}

	#[Action( 'init' )]
	/** Voegt samenvatting voor pagina's toe */
	public function add_page_excerpt_support() {
		add_post_type_support( 'page', 'excerpt' );
	}

	#[Filter( 'core_version_check_query_args' )]
	/** Verwijdert niet-essentiele gegevens voor call naar WP update server */
	public function remove_core_version_check_query_args( array $query ): array {
		unset( $query['local_package'] );
		unset( $query['blogs'] );
		unset( $query['users'] );
		unset( $query['multisite_enabled'] );
		unset( $query['initial_db_version'] );
		return $query;
	}

	#[Action( 'do_feed', 1 )]
	#[Action( 'do_feed_rdf', 1 )]
	#[Action( 'do_feed_rss', 1 )]
	#[Action( 'do_feed_rss2', 1 )]
	#[Action( 'do_feed_atom', 1 )]
	#[Action( 'do_feed_rss2_comments', 1 )]
	#[Action( 'do_feed_atom_comments', 1 )]
	/** Schakelt feed uit */
	public function disable_feed() {
		wp_safe_redirect( home_url() );
		exit;
	}

	#[Filter( 'site_status_tests' )]
	/** Verwijdert test voor automatische updates */
	public function remove_update_check( array $tests ): array {
		unset( $tests['async']['background_updates'] );
		return $tests;
	}

	#[Filter( 'embed_oembed_html' )]
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

		$url_parts = wp_parse_url( $matches[1] );
		$url = $url_parts['scheme'] . '://www.youtube-nocookie.com' . $url_parts['path'];
		$url = add_query_arg(
			[
				'rel'            => false,
				'modestbranding' => true,
				'controls'       => true,
				'fs'             => false,
			],
			$url
		);

		return str_replace( $matches[1], $url, $cache );
	}

	#[Filter( 'manage_media_columns' )]
	/** Verberg admin columns bij attachments */
	public function manage_media_columns( array $columns ): array {
		unset( $columns['author'] );
		unset( $columns['comments'] );
		return $columns;
	}

	#[Filter( 'wp_trim_excerpt' )]
	/** Plaats Lees meer button als gekozen is voor samenvatting */
	public function show_read_more_button( string $excerpt ): string {

		// alleen bij de blog
		if ( get_post_type() !== 'post' || ! has_excerpt() ) {
			return $excerpt;
		}

		return sprintf(
			'%1$s <p class="read-more-button-container"><a class="button" href="%2$s">%3$s</a></p>',
			$excerpt,
			get_permalink(),
			__( 'Lees meer', 'siw' )
		);
	}

	#[Filter( 'script_loader_tag' )]
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

	#[Action( 'template_redirect' )]
	/** Author archives doorsturen naar home page */
	public function disable_author_archive() {
		if ( is_author() ) {
			wp_safe_redirect( home_url() );
			exit;
		}
	}

	#[Action( 'template_redirect' )]
	public function redirect_attachments() {
		global $post;

		if ( $post && \is_attachment() ) {
			if ( ! empty( $post->post_parent ) ) {
				\wp_safe_redirect( \get_permalink( $post->post_parent ), \WP_Http::MOVED_PERMANENTLY );
				exit;
			} else {
				$url = \wp_get_attachment_url( $post->ID );
				if ( $url ) {
					\wp_safe_redirect( $url, \WP_Http::MOVED_PERMANENTLY );
					exit;
				}
			}
		}
	}
}
