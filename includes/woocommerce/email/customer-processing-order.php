<?php

namespace SIW\WooCommerce\Email;

/**
 * E-mail voor betaalde aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Customer_Processing_Order {
	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_subject_customer_processing_order', [ $self, 'set_subject'], 10, 2 );
		add_filter( 'woocommerce_email_heading_customer_processing_order', [ $self, 'set_heading'], 10, 2 );
		add_filter( 'wc_get_template', [ $self, 'set_template'], 10, 5 );
	}

	/**
	 * Past onderwerp aan
	 *
	 * @param string $subject
	 * @param \WC_Order $order
	 * @return string
	 */
	public function set_subject( string $subject, \WC_Order $order ) {
		return sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	}

	/**
	 * Past heading aan
	 *
	 * @param string $heading
	 * @param \WC_Order $order
	 * @return string
	 */
	public function set_heading( string $heading, \WC_Order $order ) {
		if ( 'mollie_wc_gateway_ideal' == $order->get_payment_method() || 'cod' == $order->get_payment_method() ) {
			$heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
		}
		else {
			$heading = sprintf( __( 'Bevestiging betaling aanmelding #%s', 'siw'), $order->get_order_number() );
		}
		return $heading;
	}

	/**
	 * Overschrijft template
	 *
	 * @param string $located
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_template( string $located, string $template_name, array $args, string $template_path, string $default_path ) {
		if ( 'emails/customer-processing-order.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}
}
