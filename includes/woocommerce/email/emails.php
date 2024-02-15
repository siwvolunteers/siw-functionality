<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Properties;

class Emails extends Base {

	#[Add_Filter( 'woocommerce_email_from_name' )]
	public function set_email_from_name(): string {
		return Properties::NAME;
	}

	#[Add_Filter( 'woocommerce_email_from_address' )]
	public function set_email_from_address(): string {
		return siw_get_email_settings( 'workcamp' )->get_confirmation_mail_sender();
	}

	#[Add_Filter( 'woocommerce_email_classes' )]
	public function overwrite_email_classes( array $emails ): array {
		$emails['WC_Email_New_Order'] = new New_Order( $emails['WC_Email_New_Order'] );
		$emails['WC_Email_Customer_On_Hold_Order'] = new Customer_On_Hold_Order( $emails['WC_Email_Customer_On_Hold_Order'] );
		$emails['WC_Email_Customer_Processing_Order'] = new Customer_Processing_Order( $emails['WC_Email_Customer_Processing_Order'] );
		return $emails;
	}

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
