<?php

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
		add_filter( 'the_seo_framework_sitemap_post_limit', [ $self, 'set_sitemap_post_limit' ] );
		add_filter( 'the_seo_framework_sitemap_supported_post_types', [ $self, 'set_sitemap_supported_post_types'] );
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
	public function set_metabox_priority( string $priority ) {
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
	public function set_robots( array $robots ) {
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
	public function set_sitemap_color_main( string $color ) {
		return SIW_Properties::SECONDARY_COLOR;
	}

	/**
	 * Zet de accentkleur van de sitemap
	 *
	 * @param string $color
	 * @return string
	 */
	public function set_sitemap_color_accent( string $color ) {
		return SIW_Properties::FONT_COLOR;
	}

	/**
	 * Verhoogt limiet aantal posts voor sitemap
	 *
	 * @param int $count
	 * @return int
	 */
	public function set_sitemap_post_limit( int $count ) {
		return 5000;
	}

	/**
	 * Voegt bots toe aan robot.txt
	 *
	 * @param string $output
	 * @return string
	 */
	public function set_robots_txt( string $output ) {
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
	 * @param array $post_types
	 * @return array
	 */
	public function set_sitemap_supported_post_types( array $post_types ) {

		//TODO: verwijderen na verwijderen Pinnacle Premium
		$post_types = array_diff( $post_types, ['testimonial']);
		if ( ! SIW_i18n::is_default_language() ) {

			$post_types = array_diff(
				$post_types,
				[
					'product',
					'wpm-testimonial', //TODO: verwijderen na uitfaseren Strong Testimonials
					'agenda',
					'vacatures',
					'siw_tm_country'
				]
			);
		}
		return $post_types;
	}

	/**
	 * Productarchieven toevoegen aan de sitemap 
	 *
	 * @param array $custom_urls
	 */
	public function set_sitemap_additional_urls( array $custom_urls ) {
		
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
