<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Helpers\Email_Template;

class Customer_On_Hold_Order extends \WC_Email_Customer_On_Hold_Order {

	use Order_Table;

	public function __construct( \WC_Email_Customer_On_Hold_Order $default_instance ) {
		remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', [ $default_instance, 'trigger' ], 10, 2 );
		remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', [ $default_instance, 'trigger' ], 10, 2 );
		remove_action( 'woocommerce_order_status_cancelled_to_on-hold_notification', [ $default_instance, 'trigger' ], 10, 2 );

		parent::__construct();
	}

	#[\Override]
	public function get_subject(): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $this->object->get_order_number() );
	}

	#[\Override]
	public function get_content_html(): string {

		return Email_Template::create()
			->set_template( 'woocommerce/customer-on-hold-order' )
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
