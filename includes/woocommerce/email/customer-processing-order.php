<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Attributes\Filter;
use SIW\Base;

/**
 * E-mail voor betaalde aanmeldingen
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Customer_Processing_Order extends Base {

	#[Filter( 'woocommerce_email_subject_customer_processing_order' )]
	public function set_subject( string $subject, \WC_Order $order ): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	}

	#[Filter( 'woocommerce_email_heading_customer_processing_order' )]
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
