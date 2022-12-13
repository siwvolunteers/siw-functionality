<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\I18n;
use SIW\Util\CSS;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor The SEO Framework
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://theseoframework.com/
 */
class The_SEO_Framework {


	/** Priority van TSF-metabox */
	const METABOX_PRIORITY = 'default';

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'autodescription/autodescription.php' ) ) {
			return;
		}
		$self = new self();

		/* SEO-metabox lagere prioriteit geven */
		add_filter( 'the_seo_framework_metabox_priority', fn(): string => self::METABOX_PRIORITY );

		/* Sitemap */
		add_filter( 'the_seo_framework_sitemap_color_main', fn(): string => CSS::CONTRAST_COLOR );
		add_filter( 'the_seo_framework_sitemap_color_accent', fn(): string => CSS::ACCENT_COLOR );
		add_filter( 'the_seo_framework_sitemap_additional_urls', [ $self, 'set_sitemap_additional_urls' ] );

		/* Naam auteur SEO framework niet in HTML tonen */
		add_filter( 'sybre_waaijer_<3', '__return_false' );
	}

	/** Productarchieven toevoegen aan de sitemap TODO: verplaatsen naar WooCommerce*/
	public function set_sitemap_additional_urls( array $custom_urls ): array {

		if ( ! I18n::is_default_language() ) {
			return $custom_urls;
		}
		$taxonomies = [
			Taxonomy_Attribute::CONTINENT(),
			Taxonomy_Attribute::COUNTRY(),
			Taxonomy_Attribute::TARGET_AUDIENCE(),
			Taxonomy_Attribute::WORK_TYPE(),
			Taxonomy_Attribute::LANGUAGE(),
			Taxonomy_Attribute::SDG(),
		];

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms( $taxonomy->value, [ 'hide_empty' => true ] );
			if ( is_wp_error( $terms ) ) {
				continue;
			}

			foreach ( $terms as $term ) {
				$custom_urls[] = get_term_link( $term->slug, $term->taxonomy );
			}
		}
		return $custom_urls;
	}
}
