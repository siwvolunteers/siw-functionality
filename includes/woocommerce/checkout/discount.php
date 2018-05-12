<?php
/*
(c)2018 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Korting toevoegen voor aanmeldingen met meerdere projecten
 */
add_action( 'woocommerce_cart_calculate_fees', function () {
  global $woocommerce;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

	$cart_contents = $woocommerce->cart->cart_contents;
	$count = 0;
	foreach ( $cart_contents as $line ) {
		$count++;
		if ( 2 == $count ) {
			$discount = $line['line_total'] * SIW_DISCOUNT_SECOND_PROJECT * -0.01;
			$woocommerce->cart->add_fee( __( 'Korting 2e project', 'siw' ), $discount, true, '' );
		}
		if ( 3 == $count ) {
			$discount = $line['line_total'] * SIW_DISCOUNT_THIRD_PROJECT * -0.01;
			$woocommerce->cart->add_fee( __( 'Korting 3e project', 'siw' ), $discount, true, '' );
		}
		if ( 3 < $count ) {
			$discount = $line['line_total'] * SIW_DISCOUNT_THIRD_PROJECT * -0.01;
			$woocommerce->cart->add_fee( sprintf( __( 'Korting %de project', 'siw' ), $count ), $discount, true, '' );
		}
	}

});