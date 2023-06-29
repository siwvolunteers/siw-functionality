<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Config;
use SIW\Util;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Korting
 *
 * - bij meerdere projecten tegelijk
 * - studenten/jongeren
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
class Discount extends Base {

	const STUDENT_DISCOUNT_COUPON_CODE = 'studentenkorting';
	const STUDENT_DISCOUNT_COUPON_DISCOUNT_TYPE = 'fixed_product';

	/** Past eventueel korting bij meerdere projecten toe */
	#[Action( 'woocommerce_cart_calculate_fees' )]
	public function maybe_set_bulk_discount( \WC_Cart $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$cart_contents = $cart->get_cart_contents();

		$count = 0;
		foreach ( $cart_contents as $line ) {
			++$count;
			if ( 1 < $count ) {
				$discount = $line['line_total'] * Config::get_discount_percentage_second_project() * -0.01;
				// translators: %d is een geheel getal
				$cart->add_fee( sprintf( __( 'Korting %de project', 'siw' ), $count ), $discount );
			}
		}
	}

	#[Action( 'woocommerce_checkout_update_order_review' )]
	public function maybe_set_student_discount( $post_data ) {
		if ( empty( $post_data ) ) {
			return;
		}
		wp_parse_str( $post_data, $data );

		$cart = WC()->cart;

		$student_discount_applied = $cart->has_discount( self::STUDENT_DISCOUNT_COUPON_CODE );

		$under_18 = isset( $data['billing_dob'] ) && ! empty( $data['billing_dob'] ) && Util::calculate_age( $data['billing_dob'] ) < 18;
		$student = isset( $data['billing_student'] ) && 'yes' === $data['billing_student'];

		$student_discount_applicable = $under_18 || $student;

		if ( $student_discount_applicable && ! $student_discount_applied ) {
			$cart->apply_coupon( self::STUDENT_DISCOUNT_COUPON_CODE );
		} elseif ( ! $student_discount_applicable && $student_discount_applied ) {
			$cart->remove_coupon( self::STUDENT_DISCOUNT_COUPON_CODE );
		}
	}

	#[Filter( 'woocommerce_cart_totals_coupon_label' )]
	public function set_coupon_label( string $label, \WC_Coupon $coupon ): string {
		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $label;
		}
		return $coupon->get_description();
	}

	#[Filter( 'woocommerce_cart_totals_coupon_html' )]
	public function set_coupon_html( string $coupon_html, \WC_Coupon $coupon ): string {
		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $coupon_html;
		}
		$amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code() );
		$coupon_html = '-' . wc_price( $amount );
		return $coupon_html;
	}

	/** Onderdruk message bij toevoegen verwijderen coupon */
	#[Filter( 'woocommerce_coupon_message' )]
	public function set_coupon_message( string $message, int $message_code, \WC_Coupon $coupon ): ?string {
		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $message;
		}
		return null;
	}

	#[Filter( 'woocommerce_get_shop_coupon_data' )]
	public function add_virtual_coupon( mixed $data, string|array $code, \WC_Coupon $coupon ): mixed {
		if ( ! is_string( $code ) || self::STUDENT_DISCOUNT_COUPON_CODE !== $code ) {
			return $data;
		}
		return [
			'discount_type' => self::STUDENT_DISCOUNT_COUPON_DISCOUNT_TYPE,
			'amount'        => Config::get_student_discount_amount(),
			'description'   => 'Studentenkorting',
		];
	}

	#[Filter( 'woocommerce_coupon_is_valid_for_product' )]
	public function maybe_exclude_product( bool $valid, WC_Product_Project $product, \WC_Coupon $coupon, ): bool {

		if ( self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $valid;
		}
		if ( $product->is_excluded_from_student_discount() ) {
			return false;
		}
		return $valid;
	}

	#[Filter( 'woocommerce_coupon_error' )]
	public function set_coupon_error( string $message, int $error_code, ?\WC_Coupon $coupon ): ?string {
		if ( null === $coupon || self::STUDENT_DISCOUNT_COUPON_CODE !== $coupon->get_code() ) {
			return $message;
		}
		return null;
	}
}
