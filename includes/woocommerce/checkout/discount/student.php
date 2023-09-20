<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout\Discount;

use SIW\Attributes\Add_Action;
use SIW\Config;
use SIW\Util;

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

	#[Add_Action( 'woocommerce_checkout_update_order_review' )]
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
}
