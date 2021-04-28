<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

/**
 * Aanmelding voor nieuwsbrief tijdens WooCommerce checkout
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Newsletter{

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_after_checkout_billing_form', [ $self, 'show_newsletter_signup_checkbox'] );
		add_filter( 'woocommerce_checkout_posted_data', [ $self, 'capture_newsletter_signup'] );
		add_action( 'woocommerce_checkout_order_processed', [ $self, 'process_newsletter_signup'], 10, 3 );
	}

	/** Toont checkbox voor aanmelden nieuwsbrief */
	public function show_newsletter_signup_checkbox( \WC_Checkout $checkout ) {
		woocommerce_form_field( 'newsletter_signup', [
			'type'  => 'checkbox',
			'class' => ['form-row-wide'],
			'clear' => true,
			'label' => __( 'Ja, ik wil graag de SIW nieuwsbrief ontvangen', 'siw' ),
			], $checkout->get_value( 'newsletter_signup' )
		);
	}

	/** Voegt aanmelding voor nieuwsbrief toe aan posted data */
	public function capture_newsletter_signup( array $data ) : array {
		$data['newsletter_signup'] = (int) isset( $_POST['newsletter_signup'] );
		return $data;
	}

	/** Verwerkt aanmelding voor nieuwsbrief
	 * @todo tekst aan bevestigingsmail toevoegen */
	public function process_newsletter_signup( int $order_id, array $posted_data, \WC_Order $order ) {

		if ( 1 != $posted_data['newsletter_signup'] ) {
			return;
		}
		siw_newsletter_subscribe( 
			$order->get_billing_email(),
			(int) siw_get_option( 'newsletter_list' ),
			[
				'firstname' => $order->get_billing_first_name(),
				'lastname'  => $order->get_billing_last_name(),
			]
		);
	}
}
