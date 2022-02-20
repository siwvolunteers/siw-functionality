<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout formulier
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Form {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_multistep_checkout_before_order_info', [ $self, 'show_checkout_partner_fields'] );
		add_filter( 'woocommerce_checkout_cart_item_quantity', '__return_empty_string' );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
	}

	/** Haalt checkoutvelden op */
	protected function get_checkout_fields( $checkout_fields = [] ): array {
		$checkout_fields = wp_parse_args_recursive( siw_get_data( 'workcamps/checkout-fields' ), $checkout_fields );
		return $checkout_fields;
	}

	/** Haalt secties voor checkoutvelden op */
	protected function get_checkout_sections(): array {
		$checkout_sections = siw_get_data( 'workcamps/checkout-sections' );
		return $checkout_sections;
	}

	/** Toont de extra checkoutvelden */
	public function show_checkout_partner_fields( \WC_Checkout $checkout ) {

		$checkout_sections = $this->get_checkout_sections();
		$checkout_fields = $this->get_checkout_fields();
		?>
		<h1><?php esc_html_e( 'Informatie voor partner', 'siw' );?></h1>
		<div class="woocommerce-extra-fields">
			<?php foreach ( $checkout_sections as $section => $header ) :?>
			<div id="<?= esc_attr( $section );?>">
				<h3><?= esc_html( $header );?></h3>
				<?php
				foreach ( $checkout_fields[ $section ] as $key => $field ) {
					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				}
			?>
			</div>
			<?php endforeach ?>
		</div>
		<?php
	}
}
