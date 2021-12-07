<?php declare(strict_types=1);

namespace SIW\Core;

use SIW\Properties;
use SIW\Util\CSS;

/**
 * Class om head aan te passen
 *
 * - Web app manifest
 * - Optimalisatie
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Head {

	/** Bestandsnaam van web app manifest */
	const WEB_APP_MANIFEST_FILENAME = 'manifest.json';

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'site_icon_meta_tags', [ $self, 'add_manifest_tag']);
		add_action( 'init', [ $self, 'show_web_app_manifest'] );

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

	/** Voegt tag voor web app manifest toe */
	public function add_manifest_tag( array $meta_tags ): array {
		$meta_tags[] = sprintf( '<link rel="manifest" href="%s" crossorigin="use-credentials">', get_home_url( null, self::WEB_APP_MANIFEST_FILENAME ) );
		return $meta_tags;
	}

	/** Toont web app manifest */
	public function show_web_app_manifest(): never {
		$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		//TODO: vervangen door str_ends_with() bij upgrade naar php8
		if ( ! str_ends_with( $request, self::WEB_APP_MANIFEST_FILENAME ) ) {
			return;
		}

		$data = [
			'short_name'       => 'SIW',
			'name'             => Properties::NAME,
			'description'      => esc_attr( get_bloginfo( 'description') ),
			'lang'             => str_replace( '_', '-', get_locale() ),
			'start_url'        => '.',
			'scope'            => '/',
			'display'          => 'browser',
			'orientation'      => 'any',
			'dir'              => 'ltr',
			'theme_color'      => CSS::ACCENT_COLOR,
			'background_color' => CSS::BASE_COLOR,
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
		];
		wp_send_json( $data, 200 );
	}

	/** Voegt resource hints (dns-prefetch en preconnect) toe */
	public function add_resource_hints( array $urls, string $relation_type ): array {
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
