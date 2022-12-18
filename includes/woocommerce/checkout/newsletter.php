<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Config;

/**
 * Aanmelding voor nieuwsbrief tijdens WooCommerce checkout
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Newsletter {

	const CHECKOUT_FIELD_KEY = 'newsletter_signup';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_checkout_after_terms_and_conditions', [ $self, 'show_newsletter_signup_checkbox' ] );
		add_filter( 'woocommerce_checkout_posted_data', [ $self, 'capture_newsletter_signup' ] );
		add_action( 'woocommerce_checkout_order_processed', [ $self, 'process_newsletter_signup' ], 10, 3 );
	}

	/** Toont checkbox voor aanmelden nieuwsbrief */
	public function show_newsletter_signup_checkbox() {
		woocommerce_form_field(
			self::CHECKOUT_FIELD_KEY,
			[
				'type'  => 'checkbox',
				'class' => [ 'form-row-wide' ],
				'clear' => true,
				'label' => __( 'Ja, ik wil graag de SIW nieuwsbrief ontvangen', 'siw' ),
			],
			isset( $_POST[ self::CHECKOUT_FIELD_KEY ] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
		);
	}

	/** Voegt aanmelding voor nieuwsbrief toe aan posted data */
	public function capture_newsletter_signup( array $data ): array {
		$data[ self::CHECKOUT_FIELD_KEY ] = (bool) isset( $_POST[ self::CHECKOUT_FIELD_KEY ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return $data;
	}

	/** Verwerkt aanmelding voor nieuwsbrief
	 *
	 * @todo tekst aan bevestigingsmail toevoegen */
	public function process_newsletter_signup( int $order_id, array $posted_data, \WC_Order $order ) {

		if ( true !== $posted_data[ self::CHECKOUT_FIELD_KEY ] ) {
			return;
		}
		siw_newsletter_subscribe(
			$order->get_billing_email(),
			Config::get_mailjet_newsletter_list_id(),
			[
				'firstname' => $order->get_billing_first_name(),
				'lastname'  => $order->get_billing_last_name(),
			]
		);
	}
}
