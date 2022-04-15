<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

/**
 * Proces om order meta te migreren
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Migrate_Order_Meta implements Batch_Action_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'migrate_order_data';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Migreer order meta', 'siw' );
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
		$args = [
			'limit'        => -1,
			'return'       => 'ids',
			'type'         => 'shop_order',
		];
		return wc_get_orders( $args );
	}

	/** {@inheritDoc} */
	public function process( $order_id ) {

		$order = wc_get_order( $order_id );
		if ( ! is_a( $order, \WC_Order::class ) ) {
			return;
		}

		//Meta migreren
		$meta_keys = [
			'emergencyContactName'  => 'emergency_contact_name',
			'emergencyContactPhone' => 'emergency_contact_phone',
			'language1'             => 'language_1',
			'language2'             => 'language_2',
			'language3'             => 'language_3',
			'language1Skill'        => 'language_1_skill',
			'language2Skill'        => 'language_2_skill',
			'language3Skill'        => 'language_3_skill',
			'healthIssues'          => 'health_issues',
			'volunteerExperience'   => 'volunteer_experience',
			'togetherWith'          => 'together_with',
		];

		foreach ( $meta_keys as $old_meta => $new_meta ) {
			$new_value = $order->get_meta( $old_meta );
			if ( ! empty( $new_value ) ) {
				$order->update_meta_data( $new_meta, $new_value );
			}
			$order->delete_meta_data( $old_meta );
		}

		$order->save();
	}
}
