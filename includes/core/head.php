<?php declare(strict_types=1);

namespace SIW\Core;

use SIW\Properties;

/**
 * Class om head aan te passen
 *
 * - Application manifest
 * - Optimalisatie
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Head {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_filter( 'site_icon_meta_tags', [ $self, 'add_application_manifest_tag']);
		add_action( 'init', [ $self, 'show_application_manifest'] );

		add_filter( 'wp_resource_hints', [ $self, 'add_resource_hints' ], 10 , 2 );

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
		remove_action( 'template_redirect', 'rest_output_link_header', 11 ) ;
	}

	/**
	 * Voegt tag voor application manifest toe
	 *
	 * @param array $meta_tags
	 *
	 * @return array
	 */
	public function add_application_manifest_tag( array $meta_tags ) : array {
		$meta_tags[] = sprintf( '<link rel="manifest" href="%s" crossorigin="use-credentials">', '/application.manifest' );
		return $meta_tags;
	}

	/**
	 * Toont application.manifest json
	 * 
	 * @todo Properties::PRIMARY_COLOR gebruiken voor theme_color, maar alleen als topbar niet actief is, kan dat?
	 */
	public function show_application_manifest() {
		$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;

		if ( '/application.manifest' !== $request ) {
			return;
		}

		header( 'Content-Type: application/json' );
		echo json_encode([
			'short_name'       => 'SIW',
			'name'             => Properties::NAME,
			'description'      => esc_attr( get_bloginfo( 'description') ),
			'lang'             => 'nl-NL',
			'start_url'        => '/',
			'display'          => 'browser',
			'orientation'      => 'any',
			'dir'              => 'ltr',
			'theme_color'      => '#fff',
			'background_color' => '#fff',
			'icons'            => [
				[
					'src'   => get_site_icon_url( 192 ),
					'sizes' => '192x192',
				],
				[
					'src'   => get_site_icon_url( 512 ),
					'sizes' => '512x512',
				],
			]
		]);
		die();
	}

	/**
	 * Voegt resource hints (dns-prefetch en preconnect) toe
	 *
	 * @param array $urls
	 * @param string $relation_type
	 * @return array
	 * 
	 * @todo is dns-prefetch wel nodig?
	 */
	public function add_resource_hints( array $urls, string $relation_type ) : array {
		/**
		 * URL's die gepreconnect en geprefetcht moeten worden
		 * 
		 * @param array $urls
		 */
		$preconnect_urls = apply_filters( 'siw_preconnect_urls', [] );

		if ( in_array( $relation_type, [ 'preconnect', 'dns-prefetch' ] ) ) {
			foreach ( $preconnect_urls as $preconnect_url ) {
				$urls[] = $preconnect_url;
			}
		}
		return $urls;
	}
}
