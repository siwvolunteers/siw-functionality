<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\i18n;
use SIW\Properties;

/**
 * Aanpassingen voor The SEO Framework
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://theseoframework.com/
 * @since     3.0.0
 */
class The_SEO_Framework {

	/**
	 * Maximaal aantal posts in sitemap
	 * 
	 * @var int
	 */
	const SITEMAP_POST_LIMIT = 5000;

	/**
	 * Priority van TSF-metabox
	 * 
	 * @var string
	 */
	const METABOX_PRIORITY = 'default';

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
	 * @return string
	 */
	public function set_metabox_priority() : string {
		return self::METABOX_PRIORITY;
	}

	/**
	 * Diverse archieven niet indexeren
	 *
	 * @param array $robots
	 * @return array
	 *
	 * @todo soort_evenement soort_vacature testimonial wpm-testimonial-category / verplaatsen naar WooCommerce
	 */
	public function set_robots( array $robots ) : array {
		if ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
			$robots['noindex'] = 'noindex';
		}
		return $robots;
	}

	/**
	 * Zet hoofdkleur van sitemap
	 *
	 * @return string
	 */
	public function set_sitemap_color_main() : string {
		return Properties::SECONDARY_COLOR;
	}

	/**
	 * Zet de accentkleur van de sitemap
	 *
	 * @return string
	 */
	public function set_sitemap_color_accent() : string {
		return Properties::FONT_COLOR;
	}

	/**
	 * Verhoogt limiet aantal posts voor sitemap
	 *
	 * @return int
	 */
	public function set_sitemap_post_limit() : int {
		return self::SITEMAP_POST_LIMIT;
	}

	/**
	 * Voegt bots toe aan robot.txt
	 *
	 * @param string $output
	 * @return string
	 */
	public function set_robots_txt( string $output ) : string {
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
	 * Toon alleen pagina's in Engelse sitemap
	 *
	 * @param array $post_types
	 * 
	 * @return array
	 */
	public function set_sitemap_supported_post_types( array $post_types ) : array {

		if ( ! i18n::is_default_language() ) {
			$post_types = [];
			$post_types[] = 'page';
		}
		return $post_types;
	}

	/**
	 * Productarchieven toevoegen aan de sitemap 
	 *
	 * @param array $custom_urls
	 * 
	 * @return array
	 */
	public function set_sitemap_additional_urls( array $custom_urls ) : array {
		
		if ( ! i18n::is_default_language() ) {
			return $custom_urls;
		}
		$taxonomies = [
			'product_cat',
			'pa_land',
			'pa_doelgroep',
			'pa_soort-werk',
			'pa_taal',
			'pa_sdg',
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
