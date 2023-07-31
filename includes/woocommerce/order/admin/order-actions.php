<?php declare(strict_types=1);

namespace SIW\WooCommerce\Order\Admin;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\WooCommerce\Coupon;

/**
 * Acties bij order
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Order_Actions extends Base {

	#[Filter( 'woocommerce_order_actions' )]
	public function remove_order_actions( array $actions ): array {
		unset( $actions['regenerate_download_permissions'] );
		unset( $actions['send_order_details_admin'] );
		unset( $actions['send_order_details'] );
		return $actions;
	}

	#[Filter( 'woocommerce_order_actions' )]
	public function add_order_action( array $actions, \WC_Order $order ): array {
		if ( $order->is_paid() ) {
			$actions['siw_export_to_plato'] = __( 'Exporteer naar PLATO', 'siw' );
		}
		if ( $order->is_paid() && empty( wc_get_coupon_id_by_code( $order->get_order_number() ) ) ) {
			$actions['siw_create_coupon'] = __( 'Creëer kortingscode', 'siw' );
		}
		return $actions;
	}

	#[Action( 'woocommerce_order_action_siw_export_to_plato' )]
	public function export_order_to_plato( \WC_Order $order ) {
		$data = [
			'order_id' => $order->get_id(),
		];
		siw_enqueue_async_action( 'export_plato_application', $data );
	}

	#[Action( 'woocommerce_order_action_siw_create_coupon' )]
	public function create_coupon( \WC_Order $order ) {
		Coupon::init()->create_for_order( $order->get_id() );
	}
}
