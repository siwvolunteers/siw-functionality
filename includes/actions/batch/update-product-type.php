<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Proces om product type te updaten (van variable naar projectd
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Update_Product_Type implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'update_product_type';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Product type updaten', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return false;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function select_data(): array {
		return wc_get_products([
			'return' => 'ids',
			'type'   => 'variable',
			'limit'  => -1,
		]);
	}

	/** {@inheritDoc} */
	public function process( $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! is_a( $product, \WC_Product_Variable::class ) ) {
			//return;
		}

		//Variaties verwijderen
		$variation_ids = $product->get_children();
		foreach ( $variation_ids as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			$variation->delete( true );
		}

		//Type aanpassen
		wp_set_object_terms( $product_id, WC_Product_Project::PRODUCT_TYPE, 'product_type' );

		//Meta migreren
		$meta_keys = [
			'project_id'                 => '_project_id',
			'deleted_from_plato'         => '_deleted_from_plato',
			'force_hide'                 => '_hidden',
			'use_stockphoto'             => '_use_stockphoto',
			'start_date'                 => '_start_date',
			'approval_result'            => '_approval_result',
			'description'                => '_project_description',
			'min_age'                    => '_min_age',
			'max_age'                    => '_max_age',
			'participation_fee_currency' => '_participation_fee_currency',
			'participation_fee'          => '_participation_fee',
			'latitude'                   => '_latitude',
			'longitude'                  => '_longitude',
		];

		foreach ( $meta_keys as $old => $new ) {
			$product->update_meta_data( $new, $product->get_meta( $old ) );
			$product->delete_meta_data( $old );
		}

		//SEO-meta verwijderen
		$product->delete_meta_data( '_genesis_title' );
		$product->delete_meta_data( '_genesis_description' );
	}
}