<?php declare(strict_types=1);

namespace SIW\WooCommerce\Import;

/**
 * Functies voor het genereren van productvariaties
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
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
			$variation = wc_get_product( $variation_id );
			if ( false === $variation ) {
				continue;
			}
			$variation_tariff = $variation->get_attributes()['pa_tarief'];
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

		$sale = siw_is_workcamp_sale_active();
		foreach ( $this->tariffs as $slug => $tariff ) {
			$variation = new \WC_Product_Variation;
			$variation->set_props( [
				'parent_id'         => $this->product->get_id(),
				'attributes'        => [ 'pa_tarief' => $slug ],
				'virtual'           => true,
				'regular_price'     => $tariff['regular_price'],
				'sale_price'        => $sale ? $tariff['sale_price'] : null,
				'price'             => $sale ? $tariff['sale_price'] : $tariff['regular_price'],
				'date_on_sale_from' => $sale ? date( 'Y-m-d 00:00:00', strtotime( siw_get_option( 'workcamp_sale.start_date' ) ) ) : null,
				'date_on_sale_to'   => $sale ? date( 'Y-m-d 23:59:59', strtotime( siw_get_option( 'workcamp_sale.end_date' ) ) ) : null,
			]);
			$variation->save();
		}
	}
}
