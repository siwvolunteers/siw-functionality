<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;

/**
 * WooCommerce checkout formulier
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Checkout extends Base {

	#[Filter( 'woocommerce_checkout_cart_item_quantity' )]
	private const CHECKOUT_CART_ITEM_QUANTITY = '';

	#[Action( 'woocommerce_checkout_terms_and_conditions' )]
	public function remove_checkout_privacy_policy_text() {
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
	}
}
