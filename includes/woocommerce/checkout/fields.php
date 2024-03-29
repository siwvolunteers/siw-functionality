<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;


class Fields extends Base {

	#[Add_Filter( 'woocommerce_enable_order_notes_field' )]
	private const ENABLE_ORDER_NOTES_FIELD = false;

	protected function get_checkout_fields(): array {
		return siw_get_data( 'workcamps/checkout-fields' );
	}

	protected function get_checkout_sections(): array {
		return siw_get_data( 'workcamps/checkout-sections' );
	}
	#[Add_Filter( 'woocommerce_checkout_fields' )]
	public function add_checkout_fields( $checkout_fields ): array {
		$checkout_fields = wp_parse_args_recursive( $this->get_checkout_fields(), $checkout_fields );
		return $checkout_fields;
	}

	#[Add_Action( 'woocommerce_after_checkout_billing_form' )]
	public function show_checkout_fields( \WC_Checkout $checkout ) {
		$checkout_sections = siw_get_data( 'workcamps/checkout-sections' );
		?>
		<div class="woocommerce-extra-fields">
			<?php foreach ( $checkout_sections as $section => $header ) : ?>
			<div class="woocommerce-billing-fields__field-wrapper">
				<h3 class="form-row form-row-wide"><?php echo esc_html( $header ); ?></h3>
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

	#[Add_Action( 'woocommerce_checkout_create_order' )]
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
