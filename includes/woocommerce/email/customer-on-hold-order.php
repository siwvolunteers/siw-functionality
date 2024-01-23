<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Attributes\Add_Filter;
use SIW\Base;

class Customer_On_Hold_Order extends Base {

	#[Add_Filter( 'woocommerce_email_subject_customer_on_hold_order' )]
	public function set_subject( string $subject, \WC_Order $order ): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	}

	#[Add_Filter( 'woocommerce_email_heading_customer_on_hold_order' )]
	public function set_heading( string $heading, \WC_Order $order ): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
	}
}
