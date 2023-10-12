<?php declare(strict_types=1);

namespace SIW\Actions\Batch;

use SIW\Interfaces\Actions\Batch as Batch_Action_Interface;

/**
 * Proces om oude aanmeldingen te verwijderen
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Delete_Applications implements Batch_Action_Interface {

	/** Maximale leeftijd van aanmeldingen */
	private const MAX_AGE_APPLICATIONS = 12 * MONTH_IN_SECONDS;

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'delete_applications';
	}

	/** {@inheritDoc} */
	public function get_name(): string {
		return __( 'Verwijder aanmeldingen', 'siw' );
	}

	/** {@inheritDoc} */
	public function must_be_scheduled(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function must_be_run_on_update(): bool {
		return false;
	}

	/**
	 * Selecteer de aanmeldingen van meer dan 1 jaar oud
	 *
	 * @todo geannuleerde aanmeldingen van meer dan 1 maand oud
	 * @todo configuratieconstantes voor verwijdertermijnen
	 */
	public function select_data(): array {
		$args = [
			'limit'        => -1,
			'return'       => 'ids',
			'type'         => 'shop_order',
			'date_created' => '<' . ( time() - self::MAX_AGE_APPLICATIONS ),
		];
		return wc_get_orders( $args );
	}

	/** {@inheritDoc} */
	public function process( $item ) {

		$order = wc_get_order( $item );
		if ( ! is_a( $order, \WC_Order::class ) ) {
			return false;
		}

		// Eventuele refunds verwijderen
		$refunds = $order->get_refunds();
		foreach ( $refunds as $refund ) {
			$refund->delete();
		}
		$order->delete( true );
	}
}
