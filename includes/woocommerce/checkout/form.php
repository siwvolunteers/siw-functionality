<?php

namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout formulier
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Form{
	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'add_postcode_script' ] );
		add_filter( 'woocommerce_form_field_args', [ $self, 'add_form_field_classes' ] );
		add_filter( 'woocommerce_form_field_radio', [ $self, 'add_form_field_markup' ] );
		add_filter( 'woocommerce_form_field_checkbox', [ $self, 'add_form_field_markup' ] );
		add_action( 'woocommerce_multistep_checkout_before_order_info', [ $self, 'show_checkout_partner_fields'] );
		add_filter( 'woocommerce_checkout_cart_item_quantity', '__return_empty_string' );
		add_filter( 'wc_get_template', [ $self, 'set_checkout_templates'], 10, 5 );
	}

	/**
	 * Haalt checkoutvelden op
	 *
	 * @param array $checkout_fields
	 * @return array
	 */
	protected function get_checkout_fields( $checkout_fields = [] ) {
		$checkout_fields = wp_parse_args_recursive( siw_get_data( 'workcamps/checkout-fields' ), $checkout_fields );
		return $checkout_fields;
	}

	/**
	 * Haalt secties voor checkoutvelden op
	 *
	 * @return array
	 */
	protected function get_checkout_sections() {
		$checkout_sections = siw_get_data( 'workcamps/checkout-sections' );
		return $checkout_sections;
	}

	/**
	 * Toont de extra checkoutvelden
	 *
	 * @param \WC_Checkout $checkout
	 */
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

	/**
	 * Voegt inline script voor postcode lookup toe
	 */
	public function add_postcode_script() {

		if ( is_checkout() ) {
			wp_localize_script(
				'siw-postcode',
				'siw_checkout_postcode_selectors',
				[
					'postcode'    => "#billing_postcode",
					'housenumber' => "#billing_housenumber",
					'street'      => "#billing_address_1",
					'city'        => "#billing_city",
				]
			);
		}
	}

	/**
	 * Voegt extra markup voor gestylde radiobuttons en checkboxes toe
	 *
	 * @param string $field
	 * @return string
	 */
	public function add_form_field_markup( string $field ) {
		$field = preg_replace( '/<input(.*?)>/', '<input$1><span class="control-indicator"></span>', $field );
		return $field;
	}

	/**
	 * Voegt extra classes voor gestylde radiobuttons en checkboxes toe
	 *
	 * @param array $args
	 * @return array
	 */
	public function add_form_field_classes( array $args ) {
		if ( $args['type'] == 'radio' ) {
			$args['class'][] = 'control-radio';
		}
		if ( $args['type'] == 'checkbox' ) {
			$args['class'][] = 'control-checkbox';
		}
		return $args;
	}

	/**
	 * Overschrijft templates
	 *
	 * @param string $located
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_checkout_templates( string $located, string $template_name, array $args, string $template_path, string $default_path ) {
		if ( 'checkout/payment-method.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}
}
