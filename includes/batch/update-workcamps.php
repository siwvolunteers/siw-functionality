<?php

namespace SIW\Batch;

use SIW\Util;
use SIW\WooCommerce\Import\Product_Image;

/**
 * Proces om Groepsprojecten bij te werken
 * 
 * - Tarieven
 * - Zichtbaarheid
 * - Stockfoto's
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.?
 */
class Update_Workcamps extends Job {

	/**
	 * Minimaal aantal dagen dat project in toekomst moet starten om zichtbaar te zijn
	 * 
	 * @var int
	 */
	const MIN_DAYS_BEFORE_START = 3;

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamps';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken Groepsprojecten';
	
	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Selecteer alle projecten
	 *
	 * @return array
	 */
	protected function select_data() {
		$args = [
			'return'     => 'ids',
			'limit'      => -1,
		];
		return wc_get_products( $args );
	}

	/**
	 * Werk tarieven van het groepsproject bij
	 *
	 * @param int $product_id
	 *
	 * @return mixed
	 */
	protected function task( $product_id ) {
		$this->product = wc_get_product( $product_id );
		
		/* Afbreken als product niet meer bestaat */
		if ( ! $this->product instanceof \WC_Product ) {
			return false;
		}
	
		$this->updated = false;
		$this->deleted = false;

		//TODO: delete oude producten
		$this->maybe_delete_project();

		//Als het project verwijderd is, is de rest niet meer nodig
		if ( $this->deleted ) {
			$this->increment_processed_count();
			return false;
		}

		//Bijwerken tarieven
		$this->update_tariffs();

		//Bijwerken zichtbaarheid
		$this->update_visibility();

		//Bijwerken stockfoto
		$this->update_stockphoto();

		if ( $this->updated ) {
			$this->increment_processed_count();
		}
		return false;
	}

	/**
	 * Bijwerken tarieven
	 */
	protected function update_tariffs() {
		if ( $this->product->get_meta( 'has_custom_tariff' ) ) {
			return;
		}

		$tariffs = siw_get_data( 'workcamps/tariffs' );
		$sale = Util::is_workcamp_sale_active();

		$workcamp_sale = siw_get_option( 'workcamp_sale' );
		$variations = $this->product->get_children();

		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$variation_tariff = $variation->get_attributes()['pa_tarief'];
			$tariff = $tariffs[ $variation_tariff ] ?? 'regulier';

			$regular_price = $tariff['regular_price'];
			$sale_price = $tariff['sale_price'];

			$variation->set_props([
				'regular_price'     => $regular_price,
				'sale_price'        => $sale ? $sale_price : null,
				'price'             => $sale ? $sale_price : $regular_price,
				'date_on_sale_from' => $sale ? date( 'Y-m-d 00:00:00', strtotime( $workcamp_sale['start_date'] ) ) : null,
				'date_on_sale_to'   => $sale ? date( 'Y-m-d 23:59:59', strtotime( $workcamp_sale['end_date'] ) ) : null,
			]);
			if ( ! empty( $variation->get_changes() ) ) {
				$variation->save();
				$this->updated = true;
			}
		}
	}

	/**
	 * Projecten zijn alleen zichtbaar als
	 * 
	 * - Het project over meer dan 3 dagen begint
	 * - Het project in een toegestaan land is
	 * - Er vrije plaatsen zijn
	 * - Het project niet afgekeurd is
	 */
	protected function update_visibility() {
		$country = siw_get_country( $this->product->get_meta( 'country' ) );

		$new_visibility = 'visible';
		if (
			'no' === $this->product->get_meta( 'freeplaces' )
			||
			false === $country
			||
			! $country->is_allowed()
			||
			'rejected' === $this->product->get_meta( 'approval_result' )
			||
			date( 'Y-m-d', time() + ( self::MIN_DAYS_BEFORE_START * DAY_IN_SECONDS ) ) >= $this->product->get_meta( 'start_date' )
		) {
			$new_visibility = 'hidden';
		}

		if ( $new_visibility !== $this->product->get_catalog_visibility() ) {
			$this->product->set_catalog_visibility( $new_visibility );
			if ( 'hidden' === $new_visibility ) {
				$this->product->set_featured( false );
			}
			$this->product->save();
			$this->updated = true;
		}
	}

	/**
	 * Probeer stockfoto toe te wijzen aan project
	 */
	protected function update_stockphoto() {

		//Afbreken als het project al een afbeelding heeft
		if ( $this->product->get_image_id() ) {
			return false;
		}
		
		//Eigenschappen van project ophalen: land en soort(en) werk
		$attributes = $this->product->get_attributes();
		if ( ! isset( $attributes['pa_land'] ) ) {
			return;
		}
		$country_slug = $attributes['pa_land']->get_slugs()[0];
		$country = siw_get_country( $country_slug );
		$work_type_slugs = $attributes['pa_soort-werk']->get_slugs();
		
		$work_types = array_map(
		function( $work_type_slug ) {
			return siw_get_work_type( $work_type_slug );
			},
			$work_type_slugs
		);
		
		//Stockfoto proberen te vinden
		$import_image = new Product_Image;
		$image_id = $import_image->get_stock_image( $country, $work_types );

		if ( null !== $image_id ) {
			$this->product->set_image_id( $image_id );
			$this->product->save();
			$this->updated = true;
		}
	}

	protected function maybe_delete_project() {

	}

}
