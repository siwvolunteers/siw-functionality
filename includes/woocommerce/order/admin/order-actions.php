<?php declare(strict_types=1);

namespace SIW\WooCommerce\Order\Admin;

/**
 * Acties bij order
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Order_Actions {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_order_actions', [ $self, 'remove_order_actions'] );
		add_filter( 'woocommerce_order_actions', [ $self, 'add_order_action'], 10, 2 );
		add_action( 'woocommerce_order_action_siw_export_to_plato', [ $self, 'export_order_to_plato'] );
	}

	/** Verwijdert overbodige order actions */
	public function remove_order_actions( array $actions ) : array {
		unset( $actions['regenerate_download_permissions']);
		unset( $actions['send_order_details_admin']);
		unset( $actions['send_order_details']);
		return $actions;
	}

	/** Voeg orderactie voor export naar Plato toe */
	public function add_order_action( array $actions, \WC_Order $order ): array {
		if ( $order->is_paid() ) {
			$actions['siw_export_to_plato'] = __( 'Exporteer naar PLATO', 'siw' );
		}
		return $actions;
	}
	
	/** Exporteert aanmelding naar plato */
	function export_order_to_plato( \WC_Order $order ) {
		$data = [
			'order_id' => $order->get_id(),
		];
		siw_enqueue_async_action( 'export_plato_application', $data );
	}
}
