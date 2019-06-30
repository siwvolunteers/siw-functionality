<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Aanpassingen voor The SEO Framework
 * 
 * @package   SIW\Compatibility
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Properties
 */

class SIW_Compat_The_SEO_Framework {

	/**
	 * Init
	 */
	public static function init() {

		if ( ! function_exists( 'the_seo_framework' ) ) {
			return;
		}
		$self = new self();

		/* SEO-metabox lagere prioriteit geven */
		add_filter( 'the_seo_framework_metabox_priority', [ $self, 'set_metabox_priority' ] );

		/* Robots */
		add_filter( 'the_seo_framework_robots_meta_array', [ $self, 'set_robots' ] );
		add_filter( 'the_seo_framework_robots_txt_pro', [ $self, 'set_robots_txt' ]) ; 

		/* Sitemap */
		add_filter( 'the_seo_framework_sitemap_color_main', [ $self, 'set_sitemap_color_main' ] );
		add_filter( 'the_seo_framework_sitemap_color_accent', [ $self, 'set_sitemap_color_accent' ] );
		add_filter( 'the_seo_framework_sitemap_custom_posts_count', [ $self, 'set_sitemap_custom_posts_count' ] );
		add_filter( 'the_seo_framework_sitemap_cpt_query_args', [ $self, 'set_sitemap_cpt_query_args' ] );
		add_filter( 'the_seo_framework_sitemap_exclude_cpt', [ $self, 'set_sitemap_excluded_cpt'] );
		add_filter( 'the_seo_framework_sitemap_additional_urls', [ $self, 'set_sitemap_additional_urls' ] );

		/* Naam auteur SEO framework niet in HTML tonen */
		add_filter( 'sybre_waaijer_<3', '__return_false' );
	}

	/**
	 * Past prioriteit van TSF metabox aan
	 *
	 * @param string $priority
	 * @return string
	 */
	public function set_metabox_priority( $priority ) {
		$priority = 'default';
		return $priority;
	}

	/**
	 * Diverse archieven niet indexeren
	 *
	 * @param array $robots
	 * @return array
	 *
	 * @todo soort_evenement soort_vacature testimonial wpm-testimonial-category
	 */
	public function set_robots( $robots ) {
		if ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
			$robots['noindex'] = 'noindex';	
		}
		return $robots;
	}

	/**
	 * Zet hoofdkleur van sitemap
	 *
	 * @param string $color
	 * @return string
	 */
	public function set_sitemap_color_main( $color ) {
		return SIW_Properties::SECONDARY_COLOR;
	}

	/**
	 * Zet de accentkleur van de sitemap
	 *
	 * @param string $color
	 * @return string
	 */
	public function set_sitemap_color_accent( $color ) {
		return SIW_Properties::FONT_COLOR;
	}

	/**
	 * Verhoogt limiet aantal custom posts voor sitemap
	 *
	 * @param int $count
	 * @return int
	 */
	public function set_sitemap_custom_posts_count( $count ) {
		return 5000;
	}

	/**
	 * Voegt extra query-args toe t.b.v. performance
	 *
	 * @param array $args
	 * @return array
	 */
	public function set_sitemap_cpt_query_args( $args ) {
		$args['meta_query'] = [
			'relation' => 'OR',
			[
				'key'     => '_genesis_noindex',
				'value'   => 0,
				'compare' => '=',
			],
			[
				'key'     => '_genesis_noindex',
				'compare' => 'NOT EXISTS',
			],
		];
		return $args;
	}

	/**
	 * Voegt bots toe aan robot.txt
	 *
	 * @param string $output
	 * @return string
	 */
	public function set_robots_txt( $output ) {
		$bots = siw_get_option( 'blocked_bots');

		if ( empty( $bots ) ) {
			return $output;
		}
		$output .= PHP_EOL;
	
		foreach ( $bots as $bot ) {
			$output .= "User-agent: " . esc_attr( $bot ) . PHP_EOL;
			$output .= "Disallow: /" . PHP_EOL . PHP_EOL;
		}
		return $output;
	}

	/**
	 * Verwijdert CPT's uit sitempa
	 *
	 * @param array $excluded_cpt
	 * @return array
	 */
	public function set_sitemap_excluded_cpt( $excluded_cpt ) {
		$excluded_cpt[] = 'testimonial';

		if ( ! SIW_i18n::is_default_language() ) {
			$excluded_cpt[] = 'product';
			$excluded_cpt[] = 'wpm-testimonial';
			$excluded_cpt[] = 'agenda';
			$excluded_cpt[] = 'vacatures';
		}
		return $excluded_cpt;
	}

	/**
	 * Productarchieven toevoegen aan de sitemap 
	 *
	 * @param array $custom_urls
	 */
	public function set_sitemap_additional_urls( $custom_urls ) {
		
		if ( ! SIW_i18n::is_default_language() ) {
			return $custom_urls;
		}
		$taxonomies = [
			'product_cat',
			'pa_land',
			'pa_doelgroep',
			'pa_soort-werk',
			'pa_taal',
		];
	
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( $taxonomy, [ 'hide_empty' => true ] );
			foreach ( $terms as $term ) {
				$custom_urls[] = get_term_link( $term->slug, $term->taxonomy );
			}
		}
		return $custom_urls;
	}
}
