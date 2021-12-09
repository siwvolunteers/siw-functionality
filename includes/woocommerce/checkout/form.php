<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout formulier
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Form {

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'add_postcode_script' ] );
		add_filter( 'woocommerce_form_field_args', [ $self, 'add_form_field_classes' ] );
		add_filter( 'woocommerce_form_field_checkbox', [ $self, 'add_form_field_markup' ] );
		add_action( 'woocommerce_multistep_checkout_before_order_info', [ $self, 'show_checkout_partner_fields'] );
		add_filter( 'woocommerce_checkout_cart_item_quantity', '__return_empty_string' );
	}

	/** Haalt checkoutvelden op */
	protected function get_checkout_fields( $checkout_fields = [] ) : array {
		$checkout_fields = wp_parse_args_recursive( siw_get_data( 'workcamps/checkout-fields' ), $checkout_fields );
		return $checkout_fields;
	}

	/** Haalt secties voor checkoutvelden op */
	protected function get_checkout_sections() : array {
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

	/** Voegt inline script voor postcode lookup toe */
	public function add_postcode_script() {

		wp_register_script( 'siw-checkout-postcode-lookup', SIW_ASSETS_URL . 'js/siw-checkout-postcode-lookup.js', ['siw-api-postcode-lookup'], SIW_PLUGIN_VERSION, true );

		$postcode_selectors = [
			'postcode'    => "billing_postcode",
			'housenumber' => "billing_housenumber",
			'street'      => "billing_address_1",
			'city'        => "billing_city",
		];
		wp_localize_script( 'siw-checkout-postcode-lookup', 'siw_checkout_postcode_selectors', $postcode_selectors );

		if ( is_checkout() && ! is_order_received_page() && ! is_checkout_pay_page() ) {
			wp_enqueue_script( 'siw-checkout-postcode-lookup' );
		}
	}

	/** Voegt extra markup voor gestylde checkboxes toe */
	public function add_form_field_markup( string $field ) : string {
		$field = preg_replace( '/<input(.*?)>/', '<input$1><span class="checkmark"></span>', $field );
		return $field;
	}

	/** Voegt extra classes voor gestylde radiobuttons, checkboxes en selects toe */
	public function add_form_field_classes( array $args ) : array {
		if ( $args['type'] == 'radio' ) {
			$args['class'][] = 'radio-icon';
		}
		if ( $args['type'] == 'checkbox' ) {
			$args['class'][] = 'checkbox-css';
		}
		if ( $args['type'] == 'select') {
			$args['input_class'][] = 'select-css';
		}
		
		return $args;
	}
}
