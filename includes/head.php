<?php declare(strict_types=1);

namespace SIW;

use SIW\Util\CSS;

/**
 * Class om head aan te passen
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Head {


	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'site_icon_meta_tags', [ $self, 'add_theme_color_tag' ] );

		/* Optimalisatie HEAD */
		add_filter( 'the_generator', '__return_false' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );
	}

	/** Voegt tag voor theme color toe */
	public function add_theme_color_tag( array $meta_tags ): array {
		$meta_tags[] = sprintf( '<meta name="theme-color" content="%s">', CSS::ACCENT_COLOR );
		return $meta_tags;
	}
}
