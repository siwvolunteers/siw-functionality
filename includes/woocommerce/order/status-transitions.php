<?php declare(strict_types=1);

namespace SIW\WooCommerce\Order;

/**
 * Acties bij statusovergangen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Status_Transitions {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_order_status_processing', [ $self, 'export_order_to_plato'] );
	}

	/** Exporteer betaalde aanmelding naar plato */
	public function export_order_to_plato( int $order_id ) {
		$data = [
			'order_id' => $order_id,
		];
		siw_enqueue_async_action( 'export_plato_application', $data );
	}

}

