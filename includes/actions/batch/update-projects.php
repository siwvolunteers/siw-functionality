<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

use SIW\Data\Country;
use SIW\Database_Table;
use SIW\Helpers\Database;
use SIW\WooCommerce\Import\Product_Image as Import_Product_Image;
use SIW\WooCommerce\Import\Free_Places as Import_Free_Places;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Proces om Groepsprojecten bij te werken
 * 
 * - Oude projecten verwijderen
 * - Tarieven
 * - Zichtbaarheid
 * - Stockfoto's
 * 
 * @copyright 20201 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo  Plato-afbeelding verwijderen als project al begonnen is of uit Plato verwijderd is
 */
class Update_Projects implements Batch_Action_Interface {

	/** Aantal maanden voordat project verwijderd wordt. */
	const MAX_AGE_PROJECT = 6;

	/** Aantal maanden voordat Nederlands project verwijderd wordt. */
	const MAX_AGE_DUTCH_PROJECT = 9;

	/** Minimaal aantal dagen dat project in toekomst moet starten om zichtbaar te zijn */
	const MIN_DAYS_BEFORE_START = 3;

	/** Meta key die aangeeft dat project uit Plato verwijderd is */
	const DELETED_FROM_PLATO_META = 'deleted_from_plato';

	/** Product */
	protected \WC_Product $product;

	/** {@inheritDoc} */
	public function get_id() : string {
		return 'update_projects';
	}

	/** {@inheritDoc} */
	public function get_name() : string {
		return __( 'Bijwerken projecten', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function select_data() : array {
		$args = [
			'return'     => 'ids',
			'limit'      => -1,
		];
		return wc_get_products( $args );
	}

	/** {@inheritDoc} */
	public function process( $product_id ) {
		$product = wc_get_product( $product_id );
		
		/* Afbreken als product niet meer bestaat */
		if ( ! is_a( $product, \WC_Product::class ) ) {
			return false;
		}
		$this->product = $product;

		//Als het project verwijderd is, is de rest niet meer nodig
		if ( $this->maybe_delete_project() ) {
			return;
		}

		//Bijwerken plato status
		$this->maybe_update_deleted_from_plato();

		//Bijwerken tarieven
		$this->maybe_update_tariffs();

		//Bijwerken zichtbaarheid
		$this->maybe_update_visibility();

		//Bijwerken stockfoto
		$this->maybe_set_stockphoto();
		return;
	}

	/**
	 * Bijwerken of project uit Plato verwijderd is
	 */
	protected function maybe_update_deleted_from_plato() {

		$project_ids = wp_cache_get( 'project_ids', 'siw_update_workcamps' );
		if ( false === $project_ids ) {
			$project_db = new Database( Database_Table::PLATO_PROJECTS() );
			$project_ids = $project_db->get_col( 'project_id' );
			wp_cache_set( 'project_ids', $project_ids, 'siw_update_workcamps' );
		}

		$deleted_from_plato = ! in_array( $this->product->get_meta( 'project_id' ), $project_ids );

		if ( $deleted_from_plato !== boolval( $this->product->get_meta( self::DELETED_FROM_PLATO_META ) ) ) {
			$this->product->update_meta_data( self::DELETED_FROM_PLATO_META, $deleted_from_plato );
			$this->product->save();
		}
	}

	/** Bijwerken tarieven */
	protected function maybe_update_tariffs() {
		if ( $this->product->get_meta( 'has_custom_tariff' ) ) {
			return;
		}

		$tariffs = siw_get_data( 'workcamps/tariffs' );
		$sale = siw_is_workcamp_sale_active();
		$variations = $this->product->get_children();

		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$variation_tariff = $variation->get_attributes()[Taxonomy_Attribute::TARIFF()->value];
			$tariff = $tariffs[ $variation_tariff ] ?? $tariffs['regulier'];

			$regular_price = $tariff['regular_price'];
			$sale_price = $tariff['sale_price'];

			$variation->set_props([
				'regular_price'     => $regular_price,
				'sale_price'        => $sale ? $sale_price : null,
				'price'             => $sale ? $sale_price : $regular_price,
				'date_on_sale_from' => $sale ? date( 'Y-m-d 00:00:00', strtotime( siw_get_option( 'workcamp_sale.start_date' ) ) ) : null,
				'date_on_sale_to'   => $sale ? date( 'Y-m-d 23:59:59', strtotime( siw_get_option( 'workcamp_sale.end_date' ) ) ) : null,
			]);
			if ( ! empty( $variation->get_changes() ) ) {
				$variation->save();
			}
		}
		return;
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
			$this->product->get_meta( Import_Free_Places::META_KEY )
			||
			! is_a( $country, Country::class )
			||
			! $country->is_allowed()
			||
			'rejected' === $this->product->get_meta( 'approval_result' )
			||
			date( 'Y-m-d', time() + ( self::MIN_DAYS_BEFORE_START * DAY_IN_SECONDS ) ) >= $this->product->get_meta( 'start_date' )
			||
			$this->product->get_meta( self::DELETED_FROM_PLATO_META )
			||
			$this->product->get_meta( 'force_hide' )
		) {
			$visibility = 'hidden';
		}

		if ( $visibility !== $this->product->get_catalog_visibility() ) {
			$this->product->set_catalog_visibility( $visibility );

			//Als het project verborgen wordt, moet het ook niet meer aanbevolen zijn
			if ( 'hidden' === $visibility ) {
				$this->product->set_featured( false );
			}
			$this->product->save();
		}
		return;
	}

	/** Probeer stockfoto toe te wijzen aan project */
	protected function maybe_set_stockphoto() {

		//Afbreken als het project al een afbeelding heeft
		if ( $this->product->get_image_id() ) {
			return false;
		}
		
		//Eigenschappen van project ophalen: land en soort(en) werk
		$attributes = $this->product->get_attributes();
		if ( ! isset( $attributes[ Taxonomy_Attribute::COUNTRY()->value ] ) ) {
			return;
		}
		$country_slug = $attributes[Taxonomy_Attribute::COUNTRY()->value]->get_slugs()[0];
		$country = siw_get_country( $country_slug );
		$work_type_slugs = $attributes[Taxonomy_Attribute::WORK_TYPE()->value]->get_slugs();
		
		$work_types = array_map(
			fn( string $work_type_slug ) => siw_get_work_type( $work_type_slug ),
			$work_type_slugs
		);

		//Stockfoto proberen te vinden
		$import_image = new Import_Product_Image;
		$image_id = $import_image->get_stock_image( $country, $work_types );

		if ( is_int( $image_id ) ) {
			$this->product->set_image_id( $image_id );
			$this->product->save();
		}
		return;
	}

	/** Oude projecten verwijderen */
	protected function maybe_delete_project() : bool {
	
		$start_date = $this->product->get_meta( 'start_date');
		$max_age = ( 'nederland' == $this->product->get_meta( 'country' ) ) ? self::MAX_AGE_DUTCH_PROJECT : self::MAX_AGE_PROJECT;
		$min_date = date( 'Y-m-d', time() - ( $max_age * MONTH_IN_SECONDS ) );

		//Afbreken als project nog niet oud genoeg is
		if ( $start_date > $min_date ) {
			return false;
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
			if ( ! is_a( $variation, \WC_Product_Variation::class ) ) {
				continue;
			}
			$variation->delete( true );
		}

		//Verwijder het product zelf
		$this->product->delete( true );
		return true;
	}
}
