<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Breidt WooCommerce shortcode uit met extra attributen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 *
 * @todo meta_query toevoegen: leeftijd, etc.
 */
class Shortcode extends Base {

	/** Geeft extra taxonomy attributes terug */
	protected function get_taxonomy_attributes(): array {
		return [
			'continent'    => Taxonomy_Attribute::CONTINENT(),
			'land'         => Taxonomy_Attribute::COUNTRY(),
			'sdg'          => Taxonomy_Attribute::SDG(),
			'maand'        => Taxonomy_Attribute::MONTH(),
			'soort-werk'   => Taxonomy_Attribute::WORK_TYPE(),
			'taal'         => Taxonomy_Attribute::LANGUAGE(),
			'doelgroep'    => Taxonomy_Attribute::TARGET_AUDIENCE(),
			'projectsoort' => Taxonomy_Attribute::PROJECT_TYPE(),
		];
	}

	/** Geeft extra instelling voor shortcode terug */
	protected function get_extra_attributes(): array {
		return [
			'show_button' => false,
			'button_url'  => '',
			'button_text' => '',
		];
	}

	/** Voegt extra attributen toe aan shortcode */
	#[Filter( 'shortcode_atts_products' )]
	public function add_shortcode_atts( array $out, array $pairs, array $atts, string $shortcode ): array {
		if ( 'products' !== $shortcode ) {
			return $out;
		}
		foreach ( array_keys( $this->get_taxonomy_attributes() ) as $attribute ) {
			$out[ $attribute ] = $atts[ $attribute ] ?? '';
		}

		foreach ( $this->get_extra_attributes() as $attribute => $default ) {
			$out[ $attribute ] = $atts[ $attribute ] ?? $default;
		}

		return $out;
	}

	/** Past extra attributen toe in query */
	#[Filter( 'woocommerce_shortcode_products_query' )]
	public function edit_shortcode_products_query( array $query_args, array $attributes, string $type ): array {
		if ( 'products' !== $type ) {
			return $query_args;
		}

		foreach ( $this->get_taxonomy_attributes() as $attribute => $taxonomy ) {
			if ( ! empty( $attributes[ $attribute ] ) ) {
				$terms = array_map( 'sanitize_title', explode( ',', $attributes[ $attribute ] ) );
				$query_args['tax_query'][] = [
					'taxonomy' => $taxonomy->value,
					'terms'    => $terms,
					'field'    => 'slug',
					'operator' => 'IN',
				];
			}
		}

		return $query_args;
	}

	/** Toont knop naar archief pagina */
	#[Action( 'add_archive_button' )]
	public function add_archive_button( array $attributes ) {
		if ( 'true' !== $attributes['show_button'] ) {
			return;
		}

		printf(
			'<a href="%s" class="button">%s</a>',
			esc_url( $attributes['button_url'] ),
			esc_html( $attributes['button_text'] ),
		);
	}

	/** Toont text en knop als er geen zoekresultaten zijn. */
	#[Action( 'woocommerce_shortcode_products_loop_no_results' )]
	public function add_no_results_text_and_button( array $attributes ) {
		if ( 'true' !== $attributes['show_button'] ) {
			return;
		}
		printf(
			'<p class="woocommerce-info">%s</p>',
			esc_html__( 'Er zijn helaas geen projecten gevonden die aan je zoekcriteria voldoen.', 'siw' )
		);
		printf(
			'<a href="%s" class="button">%s</a>',
			esc_url( wc_get_page_permalink( 'shop' ) ),
			esc_html__( 'Bekijk alle projecten', 'siw' )
		);
	}

}
