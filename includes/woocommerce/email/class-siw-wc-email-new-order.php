<?php

/**
 * Notificatiemail voor nieuwe aanmelding
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_WC_Email_New_Order {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_recipient_new_order', [ $self, 'set_recipient'], 10, 2 );
		add_filter( 'woocommerce_email_subject_new_order', [ $self, 'set_subject'], 10, 2 );
		add_filter( 'woocommerce_email_heading_new_order', [ $self, 'set_heading'], 10, 2 );
		add_filter( 'wc_get_template', [ $self, 'set_template'], 10, 5 );
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
		if ( 'emails/admin-new-order.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}

	/**
	 * Past ontvanger aan
	 *
	 * @param string $recipient
	 * @param WC_Order $email
	 * @return string
	 */
	public function set_recipient( string $recipient, WC_Order $order ) {
		$recipient = siw_get_option( 'workcamp_application_email')['sender'];
		return $recipient;
	}

	/**
	 * Past onderwerp aan
	 *
	 * @param string $subject
	 * @param WC_Order $order
	 * @return string
	 */
	public function set_subject( string $subject, WC_Order $order ) {
		$subject = sprintf( __( 'Nieuwe aanmelding Groepsproject (%s)', 'siw' ), $order->get_order_number() );
		return $subject;
	}

	/**
	 * Past heading aan
	 *
	 * @param string $heading
	 * @param WC_Order $order
	 * @return string
	 */
	public function set_heading( string $heading, WC_Order $order ) {
		if ( $order->has_status( 'processing' ) ) {
			$heading = sprintf( __( 'Nieuwe aanmelding (betaald)', 'siw' ), $order->get_order_number() );
		}
		else {
			$heading = sprintf( __( 'Nieuwe aanmelding (nog niet betaald)', 'siw' ), $order->get_order_number() );
		}
		return $heading;
	}
}
