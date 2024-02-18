<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Helpers\Email_Template;

class Customer_Processing_Order extends \WC_Email_Customer_Processing_Order {

	use Order_Table;

	public function __construct( \WC_Email_Customer_Processing_Order $default_instance ) {
		remove_action( 'woocommerce_order_status_cancelled_to_processing_notification', [ $default_instance, 'trigger' ], 10, 2 );
		remove_action( 'woocommerce_order_status_failed_to_processing_notification', [ $default_instance, 'trigger' ], 10, 2 );
		remove_action( 'woocommerce_order_status_on-hold_to_processing_notification', [ $default_instance, 'trigger' ], 10, 2 );
		remove_action( 'woocommerce_order_status_pending_to_processing_notification', [ $default_instance, 'trigger' ], 10, 2 );

		parent::__construct();
	}

	#[\Override]
	public function get_subject(): string {

		if ( 'mollie_wc_gateway_ideal' === $this->object->get_payment_method() ) {
			// translators: %s is het aanmeldnummer
			$subject = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $this->object->get_order_number() );
		} else {
			// translators: %s is het aanmeldnummer
			$subject = sprintf( __( 'Bevestiging betaling aanmelding #%s', 'siw' ), $this->object->get_order_number() );
		}
		return $subject;
	}

	#[\Override]
	public function get_content_html(): string {

		return Email_Template::create()
			->set_template( 'woocommerce/customer-processing-order' )
			->set_subject( $this->get_subject() )
			->set_signature( __( 'SIW', 'siw' ) )
			->add_context(
				[
					'order' => $this->object,
				]
			)
			->add_table_data( $this->get_application_data( $this->object ), __( 'Aanmelding', 'siw' ) )
			->add_table_data( $this->get_payment_data( $this->object ), __( 'Betaling', 'siw' ) )
			->add_table_data( $this->get_customer_data( $this->object ), __( 'Persoonsgegevens', 'siw' ) )
			->add_table_data( $this->get_emergency_contact_data( $this->object ), __( 'Noodcontact', 'siw' ) )
			->add_table_data( $this->get_language_data( $this->object ), __( 'Talenkennis', 'siw' ) )
			->add_table_data( $this->get_partner_info_data( $this->object ), __( 'Informatie voor partnerorganisatie', 'siw' ) )
			->generate();
	}
}
