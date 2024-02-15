<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Helpers\Email_Template;

class New_Order extends \WC_Email_New_Order {

	use Order_Table;

	#[\Override]
	public function get_recipient(): string {
		return siw_get_email_settings( 'workcamp' )->get_notification_mail_recipient();
	}

	#[\Override]
	public function get_subject(): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Nieuwe aanmelding Groepsproject (%s)', 'siw' ), $this->object->get_order_number() );
	}

	#[\Override]
	public function get_content_html(): string {
		$status = $this->object->is_paid() ? __( 'betaald', 'siw' ) : __( 'nog niet betaald', 'siw' );

		return Email_Template::create()
			->set_template( 'woocommerce/new-order' )
			// translators: %s is de betaalstatus
			->set_subject( sprintf( __( 'Nieuwe aanmelding (%s)', 'siw' ), $status ) )
			->set_signature( __( 'SIW', 'siw' ) )
			->add_context(
				[
					'application' => [
						'status'    => $status,
						'number'    => $this->object->get_order_number(),
						'admin_url' => get_edit_post_link( $this->object->get_id() ),
					],
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
