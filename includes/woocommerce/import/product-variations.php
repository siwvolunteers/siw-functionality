<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Functies voor het genereren van productvariaties
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product_Variations {

	/** Product */
	protected \WC_Product $product;

	/** Tarieven */
	protected array $tariffs;

	/** Init */
	public function __construct( \WC_Product $product, array $tariffs ) {
		$this->product = $product;
		$this->tariffs = $tariffs;
	}

	/** Bestaande variaties bijwerken */
	public function update() {
		$variations = $this->product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation = siw_get_product( $variation_id );
			if ( null === $variation ) {
				continue;
			}
			$variation_tariff = $variation->get_attributes()[Taxonomy_Attribute::TARIFF()->value];
			if ( isset( $this->tariffs[ $variation_tariff ] ) ) {
				unset( $this->tariffs[ $variation_tariff ] );
			}
			else {
				$variation->delete( true );
			}
		}
	}

	/** Variaties creëren */
	public function create() {
		if ( $this->product->get_meta( 'has_custom_tariff' ) ) {
			return;
		}

		
		foreach ( $this->tariffs as $slug => $tariff ) {
			$variation = new \WC_Product_Variation;
			$variation->set_props( [
				'parent_id'         => $this->product->get_id(),
				'attributes'        => [ Taxonomy_Attribute::TARIFF()->value => $slug ],
				'virtual'           => true,
				'regular_price'     => $tariff['regular_price'],
				'sale_price'        => null,
				'price'             => $tariff['regular_price'],
				'date_on_sale_from' => null,
				'date_on_sale_to'   => null,
			]);
			$variation->save();
		}
	}
}
