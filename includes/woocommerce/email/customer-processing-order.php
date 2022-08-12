<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

/**
 * E-mail voor betaalde aanmeldingen
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Customer_Processing_Order {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_subject_customer_processing_order', [ $self, 'set_subject' ], 10, 2 );
		add_filter( 'woocommerce_email_heading_customer_processing_order', [ $self, 'set_heading' ], 10, 2 );
	}

	/** Past onderwerp aan */
	public function set_subject( string $subject, \WC_Order $order ): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	}

	/** Past heading aan */
	public function set_heading( string $heading, \WC_Order $order ): string {
		if ( 'mollie_wc_gateway_ideal' === $order->get_payment_method() ) {
			// translators: %s is het aanmeldnummer
			$heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
		} else {
			// translators: %s is het aanmeldnummer
			$heading = sprintf( __( 'Bevestiging betaling aanmelding #%s', 'siw' ), $order->get_order_number() );
		}
		return $heading;
	}
}
