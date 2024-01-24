<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;

class Checkout extends Base {

	#[Add_Filter( 'woocommerce_checkout_cart_item_quantity' )]
	private const CHECKOUT_CART_ITEM_QUANTITY = '';

	#[Add_Action( 'woocommerce_checkout_terms_and_conditions' )]
	public function remove_checkout_privacy_policy_text() {
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
	}
}
