<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout\Discount;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Config;
use SIW\Util;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Studenten/jongerenkorting
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Student extends Virtual_Coupon {

	/** {@inheritDoc} */
	protected static function get_coupon_code(): string {
		return 'studentenkorting';
	}

	/** {@inheritDoc} */
	protected static function get_discount_type(): string {
		return 'fixed_product';
	}

	/** {@inheritDoc} */
	protected static function get_description(): string {
		return 'Studentenkorting';
	}

	/** {@inheritDoc} */
	protected static function get_amount(): float {
		return Config::get_student_discount_amount();
	}

	#[Action( 'woocommerce_checkout_update_order_review' )]
	public function maybe_set_student_discount( $post_data ) {
		if ( empty( $post_data ) ) {
			return;
		}
		wp_parse_str( $post_data, $data );

		$cart = WC()->cart;

		$under_18 = isset( $data['billing_dob'] ) && ! empty( $data['billing_dob'] ) && Util::calculate_age( $data['billing_dob'] ) < 18;
		$student = isset( $data['billing_student'] ) && 'yes' === $data['billing_student'];
		$this->set_coupon_presence( $under_18 || $student, $cart );
	}

	#[Filter( 'woocommerce_coupon_is_valid_for_product' )]
	public function maybe_exclude_product( bool $valid, WC_Product_Project $product, \WC_Coupon $coupon, ): bool {

		if ( self::get_coupon_code() !== $coupon->get_code() ) {
			return $valid;
		}
		if ( $product->is_excluded_from_student_discount() || $product->is_esc_project() ) {
			return false;
		}
		return $valid;
	}
}
