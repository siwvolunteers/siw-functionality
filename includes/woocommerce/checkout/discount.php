<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Properties;

/**
 * Korting bij meerdere projecten tegelijk
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Discount {

	const STUDENT_DISCOUNT_COUPON_CODE = 'studentenkorting';

	const STUDENT_DISCOUNT_COUPON_DISCOUNT_TYPE = 'fixed_product';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_cart_calculate_fees', [ $self, 'maybe_apply_coupon' ] );
		add_action( 'woocommerce_cart_calculate_fees', [ $self, 'calculate_discounts' ] );
	}

	public function maybe_apply_coupon( \WC_Cart $cart ) {
		
		$this->maybe_create_coupon();

		// Check of korting al wordt toegepast
		$student_discount_applied = $cart->has_discount( self::STUDENT_DISCOUNT_COUPON_CODE );

		// Check of korting van toepassing is




		$student_discount_applicable = true;

		if ( $student_discount_applicable && ! $student_discount_applied ) {
			$cart->apply_coupon( self::STUDENT_DISCOUNT_COUPON_CODE );
			
		}
		elseif ( ! $student_discount_applicable && $student_discount_applied ) {
			WC()->cart->remove_coupon( self::STUDENT_DISCOUNT_COUPON_CODE );
			// wc_clear_notices();?
		}
	

			//Kortingscode aanmaken toepassen als	
		//Als er een tiener project in de cart zit
		// studen is aangevinkt in checkout
		// deelnemer onder de 18 is
		
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

	/** Maak indien nodig kortingscode voor studentenkorting aan */
	protected function maybe_create_coupon() {
		
		if ( 0 !== wc_get_coupon_id_by_code( self::STUDENT_DISCOUNT_COUPON_CODE ) ) {
			return;
		}

		$coupon = new \WC_Coupon();
		$coupon->set_code( self::STUDENT_DISCOUNT_COUPON_CODE );
		$coupon->set_discount_type( self::STUDENT_DISCOUNT_COUPON_DISCOUNT_TYPE );
		$coupon->set_amount( Properties::STUDENT_DISCOUNT );
		$coupon->set_description( __( 'Studentenkorting', 'siw' ) );
		$coupon->save();
		return;
	}
}
