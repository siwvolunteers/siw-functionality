<?php

namespace SIW;

use SIW\Properties;
use SIW\HTML;

/**
 * Class om head aan te passen
 *
 * - Icons voor browsers
 * - Structured data voor organisatie
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
		add_action( 'wp_head', [ $self, 'add_favicons' ] );
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
	 * Voegt iconen voor diverse browsers toe
	 */
	public function add_favicons() {

		$favicons = siw_get_data( 'favicons' );

		?>
		<!-- Start favicons -->
		<?php
			foreach ( $favicons as $favicon ) {
				HTML::render_tag( $favicon['tag'], $favicon['attributes'] );
			}
		?>
		<!-- Einde favicons -->
		<?php
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
	public function add_resource_hints( array $urls, string $relation_type ) {
		/**
		 * URL's die gepreconnect en geprefetcht moeten worden
		 * 
		 * @param array $urls
		 */
		$preconnect_urls = apply_filters( 'siw_preconnect_urls', [] );

		if ( 'preconnect' === $relation_type || 'dns-prefetch' === $relation_type ) {
			foreach ( $preconnect_urls as $preconnect_url ) {
				$urls[] = $preconnect_url;
			}
		}
		return $urls;
	}

}
