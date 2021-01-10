<?php declare(strict_types=1);

namespace SIW\Batch;

/**
 * Proces om oude aanmeldingen te verwijderen
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Delete_Applications extends Job {

	/**
	 * {@inheritDoc}
	 */
	protected $action = 'delete_applications';

	/**
	 * {@inheritDoc}
	 */
	protected string $name = 'verwijderen aanmeldingen';

	/**
	 * {@inheritDoc}
	 */
	protected string $category = 'groepsprojecten';
	
	/**
	 * Selecteer de aanmeldingen van meer dan 1 jaar oud
	 * 
	 * @todo geannuleerde aanmeldingen van meer dan 1 maand oud
	 * @todo configuratieconstantes voor verwijdertermijnen
	 *
	 * @return array
	 */
	protected function select_data() : array {
		$args = [
			'limit'        => -1,
			'return'       => 'ids',
			'type'         => 'shop_order',
			'date_created' => '<' . ( time() - YEAR_IN_SECONDS ),
		];
		return wc_get_orders( $args );
	}

	/**
	 * Verwijder aanmelding
	 *
	 * @param mixed $item
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		
		$order = wc_get_order( $item );
		if ( is_a( $order, \WC_Order::class ) ) {
			return false;
		}

		//Eventuele refunds verwijderen
		$refunds = $order->get_refunds();
		foreach ( $refunds as $refund ) {
			$refund->delete();
		}

		$order->delete( true );

		$this->increment_processed_count();
		return false;
	}
}
