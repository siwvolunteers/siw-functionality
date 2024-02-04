<?php declare(strict_types=1);

namespace SIW\Jobs\Batch;

use SIW\Attributes\Add_Action;
use SIW\Data\Country;
use SIW\Data\Database_Table;
use SIW\Data\Job_Frequency;
use SIW\Facades\WooCommerce;
use SIW\Helpers\Database;
use SIW\Jobs\Scheduled_Job;
use SIW\WooCommerce\Import\Product_Image as Import_Product_Image;
use SIW\WooCommerce\Product\Admin\Approval;
use SIW\WooCommerce\Product\WC_Product_Project;

class Update_Projects extends Scheduled_Job {
	private const ACTION_HOOK = self::class;
	private const MAX_AGE_PROJECT = 6;
	private const MIN_DAYS_BEFORE_START = 3;


	protected WC_Product_Project $product;

	#[\Override]
	public function get_name(): string {
		return __( 'Bijwerken projecten', 'siw' );
	}

	#[\Override]
	protected function get_frequency(): Job_Frequency {
		return Job_Frequency::TWICE_DAILY;
	}

	#[\Override]
	public function start(): void {
		$this->enqueue_items( WooCommerce::get_product_ids(), self::ACTION_HOOK );
	}

	#[Add_Action( self::ACTION_HOOK )]
	public function update_project( string $product_id ) {
		$product = WooCommerce::get_product( $product_id );

		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return false;
		}
		$this->product = $product;

		if ( $this->maybe_delete_project() ) {
			return;
		}

		$this->maybe_delete_project_images();
		$this->maybe_update_deleted_from_plato();
		$this->maybe_update_visibility();
	}

	protected function maybe_update_deleted_from_plato() {

		$project_ids = wp_cache_get( 'project_ids', 'siw_update_workcamps' );
		if ( false === $project_ids ) {
			$project_db = new Database( Database_Table::PLATO_PROJECTS );
			$project_ids = $project_db->get_col( 'project_id' );
			wp_cache_set( 'project_ids', $project_ids, 'siw_update_workcamps' );
		}

		$deleted_from_plato = ! in_array( $this->product->get_project_id(), $project_ids, true );

		if ( boolval( $this->product->is_deleted_from_plato() ) !== $deleted_from_plato ) {
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
			! $country->workcamps()
			||
			Approval::REJECTED === $this->product->get_approval_result()
			||
			gmdate( 'Y-m-d', time() + ( self::MIN_DAYS_BEFORE_START * DAY_IN_SECONDS ) ) >= $this->product->get_start_date()
			||
			$this->product->is_deleted_from_plato()
			||
			$this->product->is_hidden()
		) {
			$visibility = 'hidden';
		}

		if ( $visibility !== $this->product->get_catalog_visibility() ) {
			$this->product->set_catalog_visibility( $visibility );

			if ( 'hidden' === $visibility ) {
				$this->product->set_featured( false );
			}
			$this->product->save();
		}
	}

	protected function delete_project_images( string $plato_project_id ) {
		$project_images_ids = get_posts(
			[
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => [
					[
						'key'     => Import_Product_Image::PLATO_PROJECT_ID_META,
						'value'   => $plato_project_id,
						'compare' => '=',
					],
				],
			]
		);
		foreach ( $project_images_ids as $project_image_id ) {
			wp_delete_attachment( $project_image_id, true );
			if ( (int) $this->product->get_image_id() === $project_image_id ) {
				$this->product->set_image_id( null );
				$this->product->save();
			}
		}
	}

	protected function maybe_delete_project_images() {
		$start_date = $this->product->get_start_date();
		$current_date = gmdate( 'Y-m-d' );
		if ( $start_date > $current_date ) {
			return;
		}
		$this->delete_project_images( $this->product->get_project_id() );
	}

	protected function maybe_delete_project(): bool {

		$start_date = $this->product->get_start_date();
		$min_date = gmdate( 'Y-m-d', time() - ( self::MAX_AGE_PROJECT * MONTH_IN_SECONDS ) );

		if ( $start_date > $min_date ) {
			return false;
		}

		$this->delete_project_images( $this->product->get_project_id() );
		$this->product->delete( true );
		return true;
	}
}
