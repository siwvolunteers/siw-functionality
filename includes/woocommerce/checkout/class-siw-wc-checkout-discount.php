<?php

/**
 * Korting bij meerdere projecten tegelijk
 * 
 * @package    SIW\WooCommerce
 * @author     Maarten Bruna
 * @copyright  2019 SIW Internationale Vrijwilligersprojecten
 * */
class SIW_WC_Checkout_Discount{

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_cart_calculate_fees', [ $self, 'calculate_discounts' ] );
	}

	/**
	 * Past korting toe bij meerdere projecten
	 *
	 * @param WC_Cart $cart
	 */
	public function calculate_discounts( WC_Cart $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$cart_contents = $cart->get_cart_contents();

		$count = 0;
		foreach ( $cart_contents as $line ) {
			$count++;
			if ( 2 == $count ) {
				$discount = $line['line_total'] * SIW_Properties::DISCOUNT_SECOND_PROJECT * -0.01;
				$cart->add_fee( __( 'Korting 2e project', 'siw' ), $discount );
			}
			if ( 3 == $count ) {
				$discount = $line['line_total'] * SIW_Properties::DISCOUNT_THIRD_PROJECT * -0.01;
				$cart->add_fee( __( 'Korting 3e project', 'siw' ), $discount );
			}
			if ( 3 < $count ) {
				$discount = $line['line_total'] * SIW_Properties::DISCOUNT_THIRD_PROJECT * -0.01;
				$cart->add_fee( sprintf( __( 'Korting %de project', 'siw' ), $count ), $discount );
			}
		}
	}
}