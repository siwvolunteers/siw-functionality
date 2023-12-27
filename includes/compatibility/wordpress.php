<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
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

	#[Add_Filter( 'rest_url_prefix' )]
	/** URL-prefix voor WP REST API */
	private const REST_API_PREFIX = 'api';

	#[Add_Filter( 'wp_default_editor' )]
	/** Default editor mode */
	private const DEFAULT_EDITOR = 'html';

	#[Add_Filter( 'big_image_size_threshold' )]
	private const BIG_IMAGE_SIZE_THRESHOLD = Properties::MAX_IMAGE_SIZE;

	#[Add_Filter( 'wp_is_application_passwords_available' )]
	private const APPLICATION_PASSWORDS_AVAILABLE = false;

	#[Add_Filter( 'comments_open' )]
	private const COMMENTS_OPEN = false;

	#[Add_Filter( 'disable_months_dropdown' )]
	private const DISABLE_MONTHS_DROPDOWN = true;

	#[Add_Filter( 'user_contactmethods' )]
	private const USER_CONTACT_METHODS = [];

	#[Add_Filter( 'get_the_archive_title_prefix' )]
	private const ARCHIVE_TITLE_PREFIX = '';

	#[Add_Filter( 'admin_email_check_interval' )]
	private const ADMIN_EMAIL_CHECK_INTERVAL = 0;

	#[Add_Action( Update::PLUGIN_UPDATED_HOOK )]
	/** Flusht rewrite rules na plugin update */
	public function flush_rewrite_rules() {
		\flush_rewrite_rules();
	}

	#[Add_Filter( 'widget_title', PHP_INT_MAX )]
	public function do_shortcode_in_widget_title( string $title ): string {
		return do_shortcode( $title );
	}

	#[Add_Filter( 'post_thumbnail_id' )]
	public function set_placeholder_featured_image( int $image_id, \WP_Post $post ): int {
		if ( 0 !== $image_id ) {
			return $image_id;
		}
		$placeholder_image_id = (int) get_option( 'woocommerce_placeholder_image', 0 );
		if ( 0 !== $placeholder_image_id ) {
			$image_id = $placeholder_image_id;
		}
		return $image_id;
	}

	#[Add_Action( 'wp_head', 1 )]
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

	#[Add_Filter( 'site_icon_meta_tags' )]
	public function add_theme_color_tag( array $meta_tags ): array {
		$meta_tags[] = sprintf( '<meta name="theme-color" content="%s">', CSS::ACCENT_COLOR );
		return $meta_tags;
	}

	#[Add_Action( 'widgets_init', 99 )]
	/** Verwijdert standaard-widgets */
	public function unregister_widgets() {
		unregister_widget( \WP_Widget_Pages::class );
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
		unregister_widget( \WP_Widget_Block::class );
	}

	#[Add_Action( 'oembed_response_data' )]
	/** Verwijdert auteurgegevens uit oembed */
	public function set_oembed_response_data( array $data ): array {
		$data['author_name'] = Properties::NAME;
		$data['author_url'] = SIW_SITE_URL;
		return $data;
	}

	#[Add_Action( 'init' )]
	/** Voegt samenvatting voor pagina's toe */
	public function add_page_excerpt_support() {
		add_post_type_support( 'page', 'excerpt' );
	}

	#[Add_Filter( 'core_version_check_query_args' )]
	/** Verwijdert niet-essentiele gegevens voor call naar WP update server */
	public function remove_core_version_check_query_args( array $query ): array {
		unset( $query['local_package'] );
		unset( $query['blogs'] );
		unset( $query['users'] );
		unset( $query['multisite_enabled'] );
		unset( $query['initial_db_version'] );
		return $query;
	}

	#[Add_Action( 'do_feed', 1 )]
	#[Add_Action( 'do_feed_rdf', 1 )]
	#[Add_Action( 'do_feed_rss', 1 )]
	#[Add_Action( 'do_feed_rss2', 1 )]
	#[Add_Action( 'do_feed_atom', 1 )]
	#[Add_Action( 'do_feed_rss2_comments', 1 )]
	#[Add_Action( 'do_feed_atom_comments', 1 )]
	/** Schakelt feed uit */
	public function disable_feed() {
		wp_safe_redirect( home_url() );
		exit;
	}

	#[Add_Filter( 'site_status_tests' )]
	/** Verwijdert test voor automatische updates */
	public function remove_update_check( array $tests ): array {
		unset( $tests['async']['background_updates'] );
		return $tests;
	}

	#[Add_Filter( 'manage_media_columns' )]
	/** Verberg admin columns bij attachments */
	public function manage_media_columns( array $columns ): array {
		unset( $columns['author'] );
		unset( $columns['comments'] );
		return $columns;
	}

	#[Add_Filter( 'wp_trim_excerpt' )]
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

	#[Add_Action( 'template_redirect' )]
	/** Author archives doorsturen naar home page */
	public function disable_author_archive() {
		if ( is_author() ) {
			wp_safe_redirect( home_url() );
			exit;
		}
	}

	#[Add_Action( 'template_redirect' )]
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

	#[Add_Filter( 'wp_nav_menu_objects' )]
	public function add_menu_ancestor_class( array $items, \stdClass $args ): array {

		// Zoek eerst menu items voor bovenliggende pagina's
		$ancestors = wp_filter_object_list(
			$items,
			[
				'current_item_ancestor' => true,
			],
			'AND',
			'ID'
		);

		// Zoek dan naar archiefpagina's van CPT's
		if ( empty( $ancestors && is_single() ) ) {
			$ancestors = wp_filter_object_list(
				$items,
				[
					'type'   => 'post_type_archive',
					'object' => get_post_type(),
				],
				'AND',
				'ID'
			);
		}

		// Fallback voor blogposts
		if ( empty( $ancestors ) && is_singular( 'post' ) ) {
			$blog_page = get_option( 'page_for_posts', true );
		}

		foreach ( $items as $item ) {
			if ( in_array( $item->ID, $ancestors, true ) || ( isset( $blog_page ) && $item->object_id === $blog_page ) ) {
				$item->classes[] = 'current-menu-ancestor';
			}
		}

		return $items;
	}
}
