<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Fields {

	/** Init */
	public static function init() {
		$self = new self();


		add_filter( 'woocommerce_checkout_fields', [ $self, 'add_checkout_fields'] );
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		add_action( 'woocommerce_checkout_create_order', [ $self, 'save_checkout_fields'], 10, 2 );
	}

	
	/** Haalt checkoutvelden op */
	protected function get_checkout_fields( array $checkout_fields = [] ) : array {
		$checkout_fields = wp_parse_args_recursive( siw_get_data( 'workcamps/checkout-fields' ), $checkout_fields );
		return $checkout_fields;
	}

	/** Slaat de extra checkoutvelden op */
	public function save_checkout_fields( \WC_Order $order, array $data ) {
		
		$checkout_fields = $this->get_checkout_fields();

		foreach ( $checkout_fields as $section => $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( isset( $data[ $key ] ) ) {
					$order->update_meta_data( $key, $data[ $key ] );
				}
			}
		}
		if ( ! empty( $data['terms'] ) ) {
			$order->update_meta_data( '_terms', $data['terms'] );
		}
	}
	


	/** Voegt de extra checkoutvelden toe */
	public function add_checkout_fields( $checkout_fields ) : array {
		$checkout_fields = $this->get_checkout_fields( $checkout_fields );
		return $checkout_fields;
	}
}
