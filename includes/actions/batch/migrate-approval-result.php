<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as I_Batch_Action;

/**
 * TODO:
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Migrate_Approval_Result implements I_Batch_Action {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'migrate_approval_result';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Migreer beoordelingsresultaat', 'siw' );
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
		return siw_get_product_ids();
	}

	/** {@inheritDoc} */
	public function process( $product_id ) {

		$product = siw_get_product( $product_id );
		/* Afbreken als product niet meer bestaat */
		if ( ! is_a( $product, WC_Product_Project::class ) ) {
			return false;
		}

		// Meta migreren
		$meta_keys = [
			'approval_result' => '_approval_result',
		];

		foreach ( $meta_keys as $old_meta => $new_meta ) {
			$new_value = $product->get_meta( $old_meta );
			if ( ! empty( $new_value ) ) {
				$product->update_meta_data( $new_meta, $new_value );
			}
			$product->delete_meta_data( $old_meta );
		}

		$product->save();
	}
}
