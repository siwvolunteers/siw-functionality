<?php declare(strict_types=1);

namespace SIW\Batch;

use SIW\Data\Country;
use SIW\Util;
use SIW\WooCommerce\Import\Product_Image;

/**
 * Proces om Groepsprojecten bij te werken
 * 
 * - Oude projecten verwijderen
 * - Tarieven
 * - Zichtbaarheid
 * - Stockfoto's
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.1.0
 * 
 * @todo  Plato-afbeelding verwijderen als project al begonnen is of uit Plato verwijderd is
 */
class Update_Workcamps extends Job {

	/**
	 * Aantal maanden voordat Groepsproject verwijderd wordt.
	 * 
	 * @var int
	 */
	const MAX_AGE_WORKCAMP = 6;

	/**
	 * Aantal maanden voordat Nederlands Groepsproject wordt.
	 * 
	 * @var int
	 */
	const MAX_AGE_DUTCH_WORKCAMP = 9;

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
	protected string $name = 'bijwerken Groepsprojecten';
	
	/**
	 * {@inheritDoc}
	 */
	protected string $category = 'groepsprojecten';

	/**
	 * Product
	 */
	protected \WC_Product $product;

	/**
	 * Is het project bijgewerkt?
	 */
	protected bool $updated;

	/**
	 * Is het project verwijderd?
	 */
	protected bool $deleted;

	/**
	 * Selecteer alle projecten
	 *
	 * @return array
	 */
	protected function select_data() : array {
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

		//delete oude producten
		$this->maybe_delete_project();

		//Als het project verwijderd is, is de rest niet meer nodig
		if ( $this->deleted ) {
			$this->increment_processed_count();
			return false;
		}

		//Bijwerken plato status
		$this->maybe_update_deleted_from_plato();

		//Bijwerken tarieven
		$this->maybe_update_tariffs();

		//Bijwerken zichtbaarheid
		$this->maybe_update_visibility();

		//Bijwerken stockfoto
		$this->maybe_set_stockphoto();

		if ( $this->updated ) {
			$this->increment_processed_count();
		}
		return false;
	}

	/**
	 * Bijwerken of project uit Plato verwijderd is
	 */
	protected function maybe_update_deleted_from_plato() {

		$imported_ids = wp_cache_get( 'imported_ids', 'siw_update_workcamps' );
		if ( false === $imported_ids ) {
			$imported_ids = array_merge(
				get_option( Import_Workcamps::IMPORTED_PROJECT_IDS_OPTION, [] ),
				get_option( Import_Dutch_Workcamps::IMPORTED_DUTCH_PROJECT_IDS_OPTION, [] )
			);
			wp_cache_set( 'imported_ids', $imported_ids, 'siw_update_workcamps' );
		}

		$deleted_from_plato = ! in_array( $this->product->get_meta( 'project_id' ), $imported_ids );

		if ( $deleted_from_plato !== boolval( $this->product->get_meta( 'deleted_from_plato' ) ) ) {
			$this->product->update_meta_data( 'deleted_from_plato', $deleted_from_plato );
			$this->product->save();
			$this->updated = true;
		}
	}

	/**
	 * Bijwerken tarieven
	 */
	protected function maybe_update_tariffs() {
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
			$tariff = $tariffs[ $variation_tariff ] ?? $tariffs['regulier'];

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
	 * - Het project niet uit Plato verwijderd is
	 * - Het project niet handmatig verborgen is
	 */
	protected function maybe_update_visibility() {
		$country = siw_get_country( $this->product->get_meta( 'country' ) );

		$visibility = 'visible';
		if (
			'no' === $this->product->get_meta( 'freeplaces' )
			||
			! is_a( $country, Country::class )
			||
			! $country->is_allowed()
			||
			'rejected' === $this->product->get_meta( 'approval_result' )
			||
			date( 'Y-m-d', time() + ( self::MIN_DAYS_BEFORE_START * DAY_IN_SECONDS ) ) >= $this->product->get_meta( 'start_date' )
			||
			$this->product->get_meta( 'deleted_from_plato' )
			||
			$this->product->get_meta( 'force_hide' )
		) {
			$visibility = 'hidden';
		}

		if ( $visibility !== $this->product->get_catalog_visibility() ) {
			$this->product->set_catalog_visibility( $visibility );

			//Als het project verborgen wordt, moet het ook niet meer aanbevolen zijn en in de carousel getoond worden
			if ( 'hidden' === $visibility ) {
				$this->product->set_featured( false );
				$this->product->update_meta_data( 'selected_for_carousel', false );
			}
			$this->product->save();
			$this->updated = true;
		}
	}

	/**
	 * Probeer stockfoto toe te wijzen aan project
	 */
	protected function maybe_set_stockphoto() {

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
			fn( string $work_type_slug ) => siw_get_work_type( $work_type_slug ),
			$work_type_slugs
		);

		//Stockfoto proberen te vinden
		$import_image = new Product_Image;
		$image_id = $import_image->get_stock_image( $country, $work_types );

		if ( is_int( $image_id ) ) {
			$this->product->set_image_id( $image_id );
			$this->product->save();
			$this->updated = true;
		}
	}

	/**
	 * Oude projecten verwijderen
	 */
	protected function maybe_delete_project() {
	
		$start_date = $this->product->get_meta( 'start_date');
		$max_age = ( 'nederland' == $this->product->get_meta( 'country' ) ) ? self::MAX_AGE_DUTCH_WORKCAMP : self::MAX_AGE_WORKCAMP;
		$min_date = date( 'Y-m-d', time() - ( $max_age * MONTH_IN_SECONDS ) );

		//Afbreken als project nog niet oud genoeg is
		if ( $start_date > $min_date ) {
			return;
		}
		
		//Verwijder projectspecifieke afbeeldingen
		$project_images = get_posts([
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => [
				[
					'key'     => 'plato_project_id',
					'value'   => $this->product->get_meta('project_id'),
					'compare' => '='
				],
			],
		]);
		foreach ( $project_images as $project_image ) {
			wp_delete_attachment( $project_image, true );
		}

		//Verwijder alle variaties
		$variations = $this->product->get_children();
		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( ! is_a( $variation, '\WC_Product_Variation' ) ) {
				continue;
			}
			$variation->delete( true );
		}

		//Verwijder het product zelf
		$this->product->delete( true );
		$this->deleted = true;
	}
}
