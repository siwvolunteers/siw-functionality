<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout formulier
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Form {

	/** Init */
	public static function init() {
		add_filter( 'woocommerce_checkout_cart_item_quantity', '__return_empty_string' );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
	}

}
