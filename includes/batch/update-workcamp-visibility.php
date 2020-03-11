<?php

namespace SIW\Batch;

/**
 * Proces om Groepsprojecten te verbergen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Update_Workcamp_Visibility extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'update_workcamp_visibility';

	/**
	 * {@inheritDoc}
	 */
	protected $name = 'bijwerken zichtbaarheid groepsprojecten';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';

	/**
	 * Minimaal aantal dagen dat project in toekomst moet starten om zichtbaar te zijn
	 * 
	 * @var int
	 */
	const MIN_DAYS_BEFORE_START = 3;

	/**
	 * Selecteer alle groepsprojecten
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
	 * Verberg het Groepsproject als het aan 1 van onderstaand voorwaarden voldoet
	 * 
	 * 
	 * - Het project begint binnen 3 dagen
	 * - Het project is in een niet-toegestaan land
	 * - Er zijn geen vrije plaatsen meer
	 *
	 * @param int $product_id
	 *
	 * @return bool
	 */
	protected function task( $product_id ) {

		$product = wc_get_product( $product_id );
		
		if ( ! $product instanceof \WC_Product ) {
			return false;
		}

		$country = siw_get_country( $product->get_meta( 'country' ) );

		$new_visibility = 'visible';
		if (
			'no' === $product->get_meta( 'freeplaces' )
			||
			false === $country
			||
			! $country->is_allowed()
			||
			date( 'Y-m-d', time() + ( self::MIN_DAYS_BEFORE_START * DAY_IN_SECONDS ) ) >= $product->get_meta( 'start_date' )
		) {
			$new_visibility = 'hidden';
		}

		if ( $new_visibility !== $product->get_catalog_visibility() ) {
			$product->set_catalog_visibility( $new_visibility );
			if ( 'hidden' === $new_visibility ) {
				$product->set_featured( false );
			}
			$product->save();
			$this->increment_processed_count();
		}
		return false;
	}
}
