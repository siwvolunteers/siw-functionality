<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Config;
use SIW\Jobs\Async\Export_To_Mailjet;

/**
 * Aanmelding voor nieuwsbrief tijdens WooCommerce checkout
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Newsletter extends Base {

	private const CHECKOUT_FIELD_KEY = 'newsletter_signup';

	#[Add_Action( 'woocommerce_checkout_after_terms_and_conditions' )]
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

	#[Add_Filter( 'woocommerce_checkout_posted_data' )]
	public function capture_newsletter_signup( array $data ): array {
		$data[ self::CHECKOUT_FIELD_KEY ] = (bool) isset( $_POST[ self::CHECKOUT_FIELD_KEY ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return $data;
	}

	#[Add_Action( 'woocommerce_checkout_order_processed' )]
	public function process_newsletter_signup( int $order_id, array $posted_data, \WC_Order $order ) {

		if ( true !== $posted_data[ self::CHECKOUT_FIELD_KEY ] ) {
			return;
		}

		$data = [
			'email'      => $order->get_billing_email(),
			'list_id'    => Config::get_mailjet_newsletter_list_id(),
			'properties' => [
				'firstname' => $order->get_billing_first_name(),
				'lastname'  => $order->get_billing_last_name(),
			],
		];

		as_enqueue_async_action( Export_To_Mailjet::class, $data );
	}
}
