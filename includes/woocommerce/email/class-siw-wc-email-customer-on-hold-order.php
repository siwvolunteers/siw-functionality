<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * E-mail voor nog niet betaalde aanmeldingen
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_WC_Email_Customer_On_Hold_Order {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_email_subject_customer_on_hold_order', [ $self, 'set_subject'], 10, 2 );
		add_filter( 'woocommerce_email_heading_customer_on_hold_order', [ $self, 'set_heading'], 10, 2 );
		add_filter( 'wc_get_template', [ $self, 'set_template'], 10, 5 );
	}

	/**
	 * Past onderwerp aan
	 *
	 * @param string $subject
	 * @param WC_Order $order
	 * @return string
	 */
	public function set_subject( $subject, $order ) {
		$subject = sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
		return $subject;
	}

	/**
	 * Past heading aan
	 *
	 * @param string $heading
	 * @param WC_Order $order
	 */
	public function set_heading( $heading, $order ) {
		$heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
		return $heading;
	}

	/**
	 * Overschrijft template
	 *
	 * @param string $located
	 * @param array $template_name
	 * @param string $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( 'emails/customer-on-hold-order.php' == $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}
}