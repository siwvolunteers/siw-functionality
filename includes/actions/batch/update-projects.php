<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

use SIW\Data\Country;
use SIW\Database_Table;
use SIW\Helpers\Database;
use SIW\WooCommerce\Import\Product_Image as Import_Product_Image;
use SIW\WooCommerce\Product\Admin\Approval;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Proces om Groepsprojecten bij te werken
 * 
 * - Oude projecten verwijderen
 * - Zichtbaarheid
 * - Stockfoto's
 * 
 * @copyright 2021-2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo  Plato-afbeelding verwijderen als project al begonnen is of uit Plato verwijderd is
 */
class Update_Projects implements Batch_Action_Interface {

	/** Aantal maanden voordat project verwijderd wordt. */
	const MAX_AGE_PROJECT = 6;

	/** Minimaal aantal dagen dat project in toekomst moet starten om zichtbaar te zijn */
	const MIN_DAYS_BEFORE_START = 3;

	/** Product */
	protected WC_Product_Project $product;

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'update_projects';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
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
	public function select_data(): array {
		return siw_get_product_ids();
	}

	/** {@inheritDoc} */
	public function process( $product_id ) {
		$product = siw_get_product( $product_id );
		
		/* Afbreken als product niet meer bestaat */
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return false;
		}
		$this->product = $product;

		//Als het project verwijderd is, is de rest niet meer nodig
		if ( $this->maybe_delete_project() ) {
			return;
		}

		//Bijwerken plato status
		$this->maybe_update_deleted_from_plato();

		//Bijwerken zichtbaarheid
		$this->maybe_update_visibility();

		//Bijwerken stockfoto
		$this->maybe_set_stockphoto();
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

		$deleted_from_plato = ! in_array( $this->product->get_project_id(), $project_ids );

		if ( $deleted_from_plato !== boolval( $this->product->is_deleted_from_plato() ) ) {
			$this->product->set_deleted_from_plato( $deleted_from_plato );
			$this->product->save();
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
		$country = $this->product->get_country();

		$visibility = 'visible';
		if (
			$this->product->is_full()
			||
			! is_a( $country, Country::class )
			||
			! $country->has_workcamps()
			||
			Approval::REJECTED === $this->product->get_approval_result()
			||
			date( 'Y-m-d', time() + ( self::MIN_DAYS_BEFORE_START * DAY_IN_SECONDS ) ) >= $this->product->get_start_date()
			||
			$this->product->is_deleted_from_plato()
			||
			$this->product->is_hidden()
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
		$country =  $this->product->get_country();
		$work_types= $this->product->get_work_types();
		
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
	protected function maybe_delete_project(): bool {
	
		$start_date = $this->product->get_start_date();
		$min_date = date( 'Y-m-d', time() - ( self::MAX_AGE_PROJECT * MONTH_IN_SECONDS ) );

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
					'value'   => $this->product->get_project_id(),
					'compare' => '='
				],
			],
		]);
		foreach ( $project_images as $project_image ) {
			wp_delete_attachment( $project_image, true );
		}

		//Verwijder het product zelf
		$this->product->delete( true );
		return true;
	}
}
