<?php

namespace SIW\Batch;

use SIW\WooCommerce\Import\Product_Image;

/**
 * Proces om stockfoto van Groepsproject te updaten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Workcamp_Stockphoto extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamp_stockphoto';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken stockfoto groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Selecteer alle groepsprojecten
	 * 
	 * @return array
	 * 
	 * @todo alleen zichtbare projecten? / slimmer filter?
	 */
	protected function select_data() {
		$args = [
			'return' => 'ids',
			'limit'  => -1,
		];
		return wc_get_products( $args );
	}

	/**
	 * Selecteert stockfoto voor product
	 *
	 * @param int $product_id
	 *
	 * @return bool
	 */
	protected function task( $product_id ) {

		$product = wc_get_product( $product_id );
		
		//Afbreken als het groepsproject niet bestaat
		if ( ! $product instanceof \WC_Product ) {
			return false;
		}

		//Afbreken als het project al een afbeelding heeft
		if ( $product->get_image_id() ) {
			return false;
		}

		//Eigenschappen van project ophalen: land en soort(en) werk
		$attributes = $product->get_attributes();
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
			$product->set_image_id( $image_id );
			$product->save();
			$this->increment_processed_count();
		}
		
		return false;
	}
}