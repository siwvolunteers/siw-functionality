<?php declare(strict_types=1);

namespace SIW\WooCommerce;

use SIW\Config;

/**
 * Creëert kortingscode
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Coupon {

	/** Type korting */
	private const DISCOUNT_TYPE = 'percent';

	/** Init */
	public static function init() {
		$self = new self();
		return $self;
	}

	/** Maakt kortingscode aan o.b.v. aanmelding */
	public function create_for_order( int $order_id ): bool {
		$order = wc_get_order( $order_id );

		// Afbreken als order niet gevonden kan worden
		if ( ! is_a( $order, \WC_Order::class ) ) {
			return false;
		}

		// Check of er al een kortingscode voor deze aanmelding bestaat
		if ( 0 !== wc_get_coupon_id_by_code( $order->get_order_number() ) ) {
			return false;
		}
		$coupon = new \WC_Coupon();
		$coupon->set_props(
			[
				'code'               => $order->get_order_number(),
				'discount_type'      => self::DISCOUNT_TYPE,
				'email_restrictions' => $order->get_billing_email(),
				'amount'             => Config::get_discount_percentage_second_project(),
				'description'        => $order->get_formatted_billing_full_name(),
				'date_expires'       => '',
				'usage_limit'        => 1,
			]
		);
		$coupon->save();
		$order->add_order_note( 'Kortingscode aangemaakt' );
		return true;
	}
}
