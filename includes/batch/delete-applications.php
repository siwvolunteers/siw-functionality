<?php


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
	protected $name = 'verwijderen aanmeldingen';

	/**
	 * {@inheritDoc}
	 */
	protected $category = 'groepsprojecten';
	
	/**
	 * Selecteer de aanmeldingen van meer dan 1 jaar oud
	 * 
	 * @todo geannuleerde aanmeldingen van meer dan 1 maand oud
	 * @todo configuratieconstantes voor verwijdertermijnen
	 *
	 * @return array
	 */
	protected function select_data() {
		$args = [
			'limit'        => -1,
			'return'       => 'ids',
			'type'         => 'shop_order',
			'date_created' => '<' . ( time() - YEAR_IN_SECONDS ),
		];
		$applications = wc_get_orders( $args );

		return $applications;
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
		if ( false == $order ) {
			return false;
		}
		$order->delete( true );

		$this->increment_processed_count();
		return false;
	}
}
