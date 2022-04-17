<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout
 * 
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Fields {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_checkout_fields', [ $self, 'add_checkout_fields'] );
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		add_action( 'woocommerce_checkout_create_order', [ $self, 'save_checkout_fields'], 10, 2 );
		add_action( 'woocommerce_after_checkout_billing_form', [ $self, 'show_checkout_fields'] );
	}
	
	/** Haalt checkoutvelden op */
	protected function get_checkout_fields(): array {
		return siw_get_data( 'workcamps/checkout-fields' );
	}

	/** Haalt checkout secties op */
	protected function get_checkout_sections(): array {
		return siw_get_data( 'workcamps/checkout-sections' );
	}

	/** Voegt de extra checkoutvelden toe */
	public function add_checkout_fields( $checkout_fields ) : array {
		$checkout_fields = wp_parse_args_recursive( $this->get_checkout_fields(), $checkout_fields );
		return $checkout_fields;
	}


	public function show_checkout_fields( \WC_Checkout $checkout ) {
		$checkout_sections = siw_get_data( 'workcamps/checkout-sections' );
		?>
		<div class="woocommerce-extra-fields">
			<?php foreach ( $checkout_sections as $section => $header ) :?>
			<div id="woocommerce-<?= esc_attr( $section );?>-fields">
			<p class="form-row form-row-wide">
				<h3><?= esc_html( $header );?></h3>
			</p>
				<?php
				foreach ( $checkout->get_checkout_fields( $section ) as $key => $field ) {
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
			?>
			</div>
			<?php endforeach ?>
		</div>
		<?php
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
}
