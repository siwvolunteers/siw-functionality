<?php declare(strict_types=1);

namespace SIW\WooCommerce\Order;

use SIW\WooCommerce\Coupon;

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
		add_action( 'woocommerce_order_status_completed', [ $self, 'create_coupon'] );
	}

	/** Exporteer betaalde aanmelding naar plato */
	public function export_order_to_plato( int $order_id ) {
		$data = [
			'order_id' => $order_id,
		];
		siw_enqueue_async_action( 'export_plato_application', $data );
	}

	/** Maak kortingscode bij afgeronde bestelling */
	public function create_coupon( int $order_id ) {
		Coupon::init()->create_for_order( $order_id );
	}

}

