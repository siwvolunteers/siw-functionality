<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Properties;

/**
 * Korting bij meerdere projecten tegelijk
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Discount{

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_cart_calculate_fees', [ $self, 'calculate_discounts' ] );
	}

	/** Past korting toe bij meerdere projecten */
	public function calculate_discounts( \WC_Cart $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$cart_contents = $cart->get_cart_contents();

		$count = 0;
		foreach ( $cart_contents as $line ) {
			$count++;
			if ( 1 < $count ) {
				$discount = $line['line_total'] * Properties::DISCOUNT_SECOND_PROJECT * -0.01;
				$cart->add_fee( sprintf( __( 'Korting %de project', 'siw' ), $count ), $discount );
			}
		}
	}
}
