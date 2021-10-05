<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

/**
 * TODO:
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Shortcode {

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'shortcode_atts_products', [ $self, 'add_shortcode_atts'], 10, 4 ); // TODO: of 3
		add_filter( 'woocommerce_shortcode_products_query', [ $self, 'edit_shortcode_products_query' ], 10, 3 );
	}


	/** TODO: */
	public function add_shortcode_atts( array $out, array $pairs, array $atts, string $shortcode ): array {
		if ( $shortcode != 'products' ) {
			return $out;
		}

		//TODO: toevoegen, land, continent, duur, SDG, soort werk, taal, leeftijd (meta)
		return $out;
	}

	/** TODO: */
	public function edit_shortcode_products_query( array $query_args, array $attributes, string $type ): array {
		if ( $type != 'products' ) {
			return $query_args;
		}

		return $query_args;
	}

}

