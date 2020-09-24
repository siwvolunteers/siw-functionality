<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

/**
 * E-mail voor nog niet betaalde aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Customer_On_Hold_Order {

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
	 * @param \WC_Order $order
	 * @return string
	 */
	public function set_subject( string $subject, \WC_Order $order ) : string {
		return sprintf( __( 'Aanmelding %s', 'siw' ), $order->get_order_number() );
	}

	/**
	 * Past heading aan
	 *
	 * @param string $heading
	 * @param \WC_Order $order
	 */
	public function set_heading( string $heading, \WC_Order $order ) : string {
		return sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $order->get_order_number() );
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
	public function set_template( string $located, string $template_name, array $args, string $template_path, string $default_path ) : string {
		if ( 'emails/customer-on-hold-order.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}
}
