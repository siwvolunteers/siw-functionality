<?php

namespace SIW\WooCommerce\Email;

/**
 * E-mail voor betaalde aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Customer_Processing_Order {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_subject_customer_processing_order', [ $self, 'set_subject'], 10, 2 );
		add_filter( 'woocommerce_email_heading_customer_processing_order', [ $self, 'set_heading'], 10, 2 );
		add_filter( 'wc_get_template', [ $self, 'set_template'], 10, 5 );
	}

	/** Past onderwerp aan */
	public function set_subject( string $subject, \WC_Order $order ) : string {
		return sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	}

	/** Past heading aan */
	public function set_heading( string $heading, \WC_Order $order ) : string {
		if ( 'mollie_wc_gateway_ideal' == $order->get_payment_method() || 'cod' == $order->get_payment_method() ) {
			$heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
		}
		else {
			$heading = sprintf( __( 'Bevestiging betaling aanmelding #%s', 'siw'), $order->get_order_number() );
		}
		return $heading;
	}

	/** Overschrijft template */
	public function set_template( string $located, string $template_name, array $args, string $template_path, string $default_path ) : string {
		if ( 'emails/customer-processing-order.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . 'woocommerce/'. $template_name;
		}
		return $located;
	}
}
