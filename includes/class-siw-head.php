<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class om head aan te passen
 *
 * - Icons voor browsers
 * - Site verificatie
 * - Structured data voor organisatie
 * - Optimalisatie
 * 
 * @package   SIW
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Formatting
 * @uses      SIW_Properties
 */
class SIW_Head {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_head', [ $self, 'add_favicons' ] );
		add_action( 'wp_head', [ $self, 'add_site_verification' ] );
		add_filter( 'wp_resource_hints', [ $self, 'add_resource_hints' ], 10 , 2 );
		add_filter( 'wp_head', [ $self, 'add_organisation_json_ld' ] );

		/* Optimalisatie HEAD */
		add_filter( 'the_generator', '__return_false' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'feed_links', 2 );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'template_redirect', 'rest_output_link_header', 11, 0) ;
	}

	/**
	 * Voegt iconen voor diverse browsers toe
	 */
	public function add_favicons() {

		$base_url = wp_make_link_relative( SIW_ASSETS_URL . 'favicons/' );
		?>
	
		<!-- Start favicons -->
		<?php
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'apple-touch-icon', 'sizes' => '180x180', 'href' => esc_url( "{$base_url}apple-touch-icon.png" ) ] );
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32', 'href' => esc_url( "{$base_url}favicon-32x32.png" ) ] );
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '192x192', 'href' => esc_url( "{$base_url}android-chrome-192x192.png" ) ] );
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16', 'href' => esc_url( "{$base_url}favicon-16x16.png" ) ] );
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'manifest', 'href' => esc_url( "{$base_url}manifest.json" ) ] );
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'mask-icon', 'href' => esc_url( "{$base_url}safari-pinned-tab.svg" ), 'color' => esc_attr( SIW_Properties::PRIMARY_COLOR ) ] );
			echo SIW_Formatting::generate_tag( 'link', [ 'rel' => 'shortcut icon', 'href' => esc_url( "{$base_url}favicon.ico" ) ] );
			echo SIW_Formatting::generate_tag( 'meta', [ 'rel' => 'msapplication-config', 'href' => esc_url( "{$base_url}browserconfig.xml" ) ] );
		?>
		<!-- Einde favicons -->
		<?php
	}

	/**
	 * Voegt siteverificatie voor Google en Bing toe.
	 */
	public function add_site_verification() {
		if ( ! is_front_page() ) {
			return;
		}
		echo '<!-- Start site verificatie -->';
		$google = siw_get_setting( 'google_search_console_verification' );
		if ( $google ) {
			echo SIW_Formatting::generate_tag( 'meta', [ 'name' => 'google-site-verification', 'content' => $google ] );
		}
		$bing = siw_get_setting( 'bing_webmaster_tools_verification' );
		if ( $bing ) {
			echo SIW_Formatting::generate_tag( 'meta', [ 'name' => 'msvalidate.01', 'content' => $bing ] );
		}
		echo '<!-- Einde site verificatie -->';
	}

	/**
	 * Voegt resource hints (dns-prefetch en preconnect) toe
	 *
	 * @param array $urls
	 * @param string $relation_type
	 * @return array
	 */
	public function add_resource_hints( $urls, $relation_type ) {
		if ( 'dns-prefetch' === $relation_type ) {
			$urls[] = 'www.google-analytics.com';
			$urls[] = 'maps.googleapis.com';
			$urls[] = 'maps.google.com';
			$urls[] = 'maps.gstatic.com';
			$urls[] = 'csi.gstatic.com';
		}
	
		if ( 'preconnect' === $relation_type ) {
			$urls[] = [
				'href' => 'https://www.google-analytics.com',
				'crossorigin',
			];
		}
		return $urls;
	}

	/**
	 * Voegt structured data (JSON-LD) voor organisatiegegevens toe
	 * 
	 * @todo tonen op contactpagina i.p.v. op homepagina
	 */
	public function add_organisation_json_ld() {
		if ( ! is_front_page() ) {
			return;
		}

		$data = [
			'@context'      => 'http://schema.org',
			'@type'         => 'Organization',
			'name'          => SIW_Properties::NAME,
			'legalName'     => SIW_Properties::STATUTORY_NAME,
			'url'           => SIW_SITE_URL,
			"logo"          => wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ),
			"foundingDate"  => SIW_Properties::FOUNDING_DATE,
			"address"       => [
				'@type'           => "PostalAddress",
				'streetAddress'   => SIW_Properties::ADDRESS,
				'addressLocality' => SIW_Properties::CITY,
				'postalCode'      => SIW_Properties::POSTCODE,
				'addressRegion'   => SIW_Properties::CITY,
				'addressCountry'  => 'NL',
			],
			"contactPoint"  => [
				"@type"           => "ContactPoint",
				"contactType"     => "customer support",
				"telephone"       => SIW_Properties::PHONE_INTERNATIONAL,
				"email"           => SIW_Properties::EMAIL,
				"hoursAvailable"  => [
					[
						"@type"      => "OpeningHoursSpecification",
						"dayOfWeek"  => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
						"opens"      => SIW_Properties::OPENING_TIME,
						"closes"     => SIW_Properties::CLOSING_TIME,
					],
				]
			],
			"sameAs"        => [ 
				SIW_Properties::FACEBOOK_URL,
				SIW_Properties::TWITTER_URL,
				SIW_Properties::INSTAGRAM_URL,
				SIW_Properties::LINKEDIN_URL,
				SIW_Properties::YOUTUBE_URL,
			],
		];
		echo SIW_Formatting::generate_json_ld( $data );
	}
}
