<?php declare(strict_types=1);

namespace SIW\WooCommerce\Email;

/**
 * Notificatiemail voor nieuwe aanmelding
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class New_Order {

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
	public function set_template( string $located, string $template_name, array $args, string $template_path, string $default_path ) : string {
		if ( 'emails/admin-new-order.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}

	/**
	 * Past ontvanger aan
	 *
	 * @return string
	 */
	public function set_recipient() : string {
		return siw_get_email_settings( 'workcamp')['email'];
	}

	/**
	 * Past onderwerp aan
	 *
	 * @param string $subject
	 * @param \WC_Order $order
	 * @return string
	 */
	public function set_subject( string $subject, \WC_Order $order ) : string {
		return sprintf( __( 'Nieuwe aanmelding Groepsproject (%s)', 'siw' ), $order->get_order_number() );
	}

	/**
	 * Past heading aan
	 *
	 * @param string $heading
	 * @param \WC_Order $order
	 * @return string
	 */
	public function set_heading( string $heading, \WC_Order $order ) : string {
		if ( $order->has_status( 'processing' ) ) {
			$heading = sprintf( __( 'Nieuwe aanmelding (betaald)', 'siw' ), $order->get_order_number() );
		}
		else {
			$heading = sprintf( __( 'Nieuwe aanmelding (nog niet betaald)', 'siw' ), $order->get_order_number() );
		}
		return $heading;
	}
}
