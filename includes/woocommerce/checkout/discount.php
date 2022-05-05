<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Properties;
use SIW\Util;

/**
 * Korting
 *
 * - bij meerdere projecten tegelijk
 * - studenten/jongeren
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Discount {

	const STUDENT_DISCOUNT_COUPON_CODE = 'studentenkorting';
	const STUDENT_DISCOUNT_COUPON_DISCOUNT_TYPE = 'fixed_product';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_cart_calculate_fees', [ $self, 'maybe_set_bulk_discount' ] );
		add_action( 'woocommerce_checkout_update_order_review', [ $self, 'maybe_set_student_discount' ] );
		add_filter( 'woocommerce_cart_totals_coupon_label', [ $self, 'set_coupon_label' ], 10, 2 );
		add_filter( 'woocommerce_cart_totals_coupon_html', [ $self, 'set_coupon_html' ], 20, 2 );
		add_filter( 'woocommerce_coupon_message', [ $self, 'set_coupon_message' ], 10, 3 );
	}

	/** Past eventueel korting bij meerdere projecten toe */
	public function maybe_set_bulk_discount( \WC_Cart $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$cart_contents = $cart->get_cart_contents();

		$count = 0;
		foreach ( $cart_contents as $line ) {
			$count++;
			if ( 1 < $count ) {
				$discount = $line['line_total'] * Properties::DISCOUNT_SECOND_PROJECT * -0.01;
				// translators: %d is een geheel getal
				$cart->add_fee( sprintf( __( 'Korting %de project', 'siw' ), $count ), $discount );
			}
		}
	}

	/** Pas eventueel studentenkorting toe */
	public function maybe_set_student_discount( $post_data ) {
		if ( empty( $post_data ) ) {
			return;
		}
		wp_parse_str( $post_data, $data );

		$cart = WC()->cart;

		$student_discount_applied = $cart->has_discount( self::STUDENT_DISCOUNT_COUPON_CODE );
		$student_discount_applicable = false;

		$under_18 = isset( $data['billing_dob'] ) && ! empty( $data['billing_dob'] ) && Util::calculate_age( $data['billing_dob'] ) < 18;
		$student = isset( $data['billing_student'] ) && 'yes' === $data['billing_student'];

		if ( $under_18 || $student ) {
			$this->maybe_create_coupon();
			$student_discount_applicable = true;
		}

		if ( $student_discount_applicable && ! $student_discount_applied ) {
			$cart->apply_coupon( self::STUDENT_DISCOUNT_COUPON_CODE );
		} elseif ( ! $student_discount_applicable && $student_discount_applied ) {
			$cart->remove_coupon( self::STUDENT_DISCOUNT_COUPON_CODE );
		}
	}

	/** Zet het label van de kortingscode */
	public function set_coupon_label( string $label, \WC_Coupon $coupon ): string {
		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $label;
		}
		return $coupon->get_description();

	}

	/** Zet de html voor het bedrag van de kortingscode */
	public function set_coupon_html( string $coupon_html, \WC_Coupon $coupon ): string {
		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $coupon_html;
		}
		$amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code() );
		$coupon_html = '-' . wc_price( $amount );
		return $coupon_html;
	}

	/** Onderdruk message bij toevoegen verwijderen coupon */
	public function set_coupon_message( string $message, int $message_code, \WC_Coupon $coupon ): ?string {
		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $message;
		}
		return null;
	}


	/** Maak indien nodig kortingscode voor studentenkorting aan TODO: updaten bij gewijzigd percentage?*/
	protected function maybe_create_coupon() {

		if ( 0 !== wc_get_coupon_id_by_code( self::STUDENT_DISCOUNT_COUPON_CODE ) ) {
			return;
		}

		$coupon = new \WC_Coupon();
		$coupon->set_code( self::STUDENT_DISCOUNT_COUPON_CODE );
		$coupon->set_discount_type( self::STUDENT_DISCOUNT_COUPON_DISCOUNT_TYPE );
		$coupon->set_amount( Properties::STUDENT_DISCOUNT_AMOUNT );
		$coupon->set_description( 'Studentenkorting' );
		$coupon->save();
	}
}
