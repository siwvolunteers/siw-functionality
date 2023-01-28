<?php declare(strict_types=1);

namespace SIW\Widgets;

use SIW\Util\HTML;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Widget met contactinformatie
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 *
 * @widget_data
 * Widget Name: SIW: Snel Zoeken - resultaten
 * Description: Toont zoekformulier
 * Author: SIW Internationale Vrijwilligersprojecten
 * Author URI: https://www.siw.nl
 */
class Quick_Search_Results extends Widget {

	const DESTINATION = 'bestemming';
	const MONTH = 'maand';

	/** {@inheritDoc} */
	protected function get_id(): string {
		return 'quick_search_results';
	}

	/** {@inheritDoc} */
	protected function get_name(): string {
		return __( 'Snel Zoeken - resultaat', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_description(): string {
		return __( 'Toont zoekresultaten', 'siw' );
	}

	/** {@inheritDoc} */
	protected function get_template_id(): string {
		return Widget::DEFAULT_TEMPLATE_ID;
	}

	/** {@inheritDoc} */
	protected function get_dashicon(): string {
		return 'search';
	}

	/** {@inheritDoc} */
	protected function supports_title(): bool {
		return true;
	}

	/** {@inheritDoc} */
	protected function supports_intro(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function initialize() {
		add_filter( 'query_vars', [ $this, 'register_query_vars' ] );
		add_filter( 'rocket_cache_query_strings', [ $this, 'register_query_vars' ] );
	}

	/** Registreert query vars */
	public function register_query_vars( array $vars ): array {
		$vars[] = self::DESTINATION;
		$vars[] = self::MONTH;
		return $vars;
	}

	/** {@inheritDoc} */
	public function get_template_variables( $instance, $args ) {

		// TODO:refactor
		$url = wc_get_page_permalink( 'shop' );
		$text = __( 'Bekijk alle projecten', 'siw' );

		$attributes = [
			'limit'      => 6,
			'columns'    => 3,
			'orderby'    => 'rand',
			'visibility' => 'visible',
			'cache'      => 'false',
		];

		/* Verwerk zoekargument bestemming*/
		$category_slug  = sanitize_key( get_query_var( self::DESTINATION, false ) );
		$category       = get_term_by( 'slug', $category_slug, Taxonomy_Attribute::CONTINENT()->value );

		if ( is_a( $category, \WP_Term::class ) ) {
			$attributes['continent'] = $category_slug;
			$url = get_term_link( $category->term_id );
			// translators: %s is een continent
			$text .= SPACE . sprintf( __( 'in %s', 'siw' ), $category->name );
		}

		/* Verwerk zoekargument maand*/
		$month_slug = sanitize_key( get_query_var( self::MONTH, false ) );
		$month      = get_term_by( 'slug', $month_slug, Taxonomy_Attribute::MONTH()->value );
		if ( is_a( $month, \WP_Term::class ) ) {
			$attributes['maand'] = $month_slug;
			$url       = add_query_arg( 'filter_maand', $month_slug, $url );
			// translators: %s is een maand
			$text .= SPACE . sprintf( __( 'in %s', 'siw' ), strtolower( $month->name ) );
		}

		$attributes['show_button'] = 'true';
		$attributes['button_url'] = $url;
		$attributes['button_text'] = $text;

		return [
			'content' => sprintf( '[products %s]', HTML::generate_attributes( $attributes ) ),
		];
	}
}
