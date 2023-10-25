<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

use SIW\Attributes\Add_Filter;
use SIW\Base;

/**
 * Notificatiemail voor nieuwe aanmelding
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class New_Order extends Base {

	#[Add_Filter( 'woocommerce_email_recipient_new_order' )]
	public function set_recipient(): string {
		return siw_get_email_settings( 'workcamp' )->get_notification_mail_recipient();
	}

	#[Add_Filter( 'woocommerce_email_subject_new_order' )]
	public function set_subject( string $subject, \WC_Order $order ): string {
		// translators: %s is het aanmeldnummer
		return sprintf( __( 'Nieuwe aanmelding Groepsproject (%s)', 'siw' ), $order->get_order_number() );
	}

	#[Add_Filter( 'woocommerce_email_heading_new_order' )]
	public function set_heading( string $heading, \WC_Order $order ): string {
		if ( $order->has_status( 'processing' ) ) {
			$heading = sprintf( __( 'Nieuwe aanmelding (betaald)', 'siw' ), $order->get_order_number() );
		} else {
			$heading = sprintf( __( 'Nieuwe aanmelding (nog niet betaald)', 'siw' ), $order->get_order_number() );
		}
		return $heading;
	}
}
