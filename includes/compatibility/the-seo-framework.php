<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Filter;
use SIW\Base;
use SIW\I18n;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;
use SIW\Util\CSS;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor The SEO Framework
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://theseoframework.com/
 */
class The_SEO_Framework extends Base implements I_Plugin {

	#[Filter( 'the_seo_framework_metabox_priority' )]
	private const METABOX_PRIORITY = 'default';

	#[Filter( 'sybre_waaijer_<3' )]
	private const SHOW_AUTHOR_IN_HTML_COMMENT = false;

	#[Filter( 'the_seo_framework_sitemap_color_main' )]
	private const SITEMAP_COLOR_MAIN = CSS::CONTRAST_COLOR;

	#[Filter( 'the_seo_framework_sitemap_color_accent' )]
	private const SITEMAP_COLOR_ACCENT = CSS::ACCENT_COLOR;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'autodescription/autodescription.php';
	}

	#[Filter( 'the_seo_framework_sitemap_additional_urls' )]
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
