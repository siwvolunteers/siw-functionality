<?php declare(strict_types=1);

namespace SIW\WooCommerce\Order;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\WooCommerce\Coupon;

/**
 * Acties bij statusovergangen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Status_Transitions extends Base {

	#[Action( 'woocommerce_order_status_processing' )]
	public function export_order_to_plato( int $order_id ) {
		$data = [
			'order_id' => $order_id,
		];
		siw_enqueue_async_action( 'export_plato_application', $data );
	}

	#[Action( 'woocommerce_order_status_completed' )]
	public function create_coupon( int $order_id ) {
		Coupon::init()->create_for_order( $order_id );
	}

}
