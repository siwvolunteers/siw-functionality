<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Breidt WooCommerce shortcode uit met extra attributen
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo meta_query toevoegen: leeftijd, etc.
 */
class Shortcode {

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'shortcode_atts_products', [ $self, 'add_shortcode_atts'], 10, 4 );
		add_filter( 'woocommerce_shortcode_products_query', [ $self, 'edit_shortcode_products_query' ], 10, 3 );
	}

	/** Geeft extra taxonomy attributes terug */
	protected function get_extra_attributes(): array {
		return [
			'continent'   => Taxonomy_Attribute::CONTINENT(),
			'land'        => Taxonomy_Attribute::COUNTRY(),
			'projectduur' => Taxonomy_Attribute::DURATION(),
			'sdg'         => Taxonomy_Attribute::SDG(),
			'maand'       => Taxonomy_Attribute::MONTH(),
			'soort-werk'  => Taxonomy_Attribute::WORK_TYPE(),
			'taal'        => Taxonomy_Attribute::LANGUAGE(),
			'doelgroep'   => Taxonomy_Attribute::TARGET_AUDIENCE(),
		];
	}

	/** Voegt extra attributen toe aan shortcode */
	public function add_shortcode_atts( array $out, array $pairs, array $atts, string $shortcode ): array {
		if ( $shortcode !== 'products' ) {
			return $out;
		}
		foreach ( array_keys( $this->get_extra_attributes() ) as $attribute ) {
			$out[ $attribute ] = $atts[ $attribute ] ?? '';
		}
		return $out;
	}

	/** Past extra attributen toe in query */
	public function edit_shortcode_products_query( array $query_args, array $attributes, string $type ): array {
		if ( $type !== 'products' ) {
			return $query_args;
		}

		foreach ( $this->get_extra_attributes() as $attribute => $taxonomy ) {
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

}