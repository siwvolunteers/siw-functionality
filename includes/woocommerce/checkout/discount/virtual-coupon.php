<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout\Discount;

use SIW\Attributes\Add_Filter;
use SIW\Base;

abstract class Virtual_Coupon extends Base {

	abstract protected static function get_coupon_code(): string;

	/**
	 * Geeft type korting terug
	 * TODO: enum van maken
	 *
	 * Mogelijke waardes:
	 * - fixed_cart
	 * - fixed_product
	 * - percent
	 */
	abstract protected static function get_discount_type(): string;

	abstract protected static function get_description(): string;

	abstract protected static function get_amount(): float;

	#[Add_Filter( 'woocommerce_get_shop_coupon_data' )]
	public function add_virtual_coupon( mixed $data, string|array $code, \WC_Coupon $coupon ): mixed {
		if ( ! is_string( $code ) || static::get_coupon_code() !== $code ) {
			return $data;
		}

		return wp_parse_args_recursive(
			[
				'discount_type' => static::get_discount_type(),
				'amount'        => static::get_amount(),
				'description'   => static::get_description(),
			],
			$this->get_coupon_data()
		);
	}

	protected function get_coupon_data(): array {
		return [];
	}

	#[Add_Filter( 'woocommerce_cart_totals_coupon_label' )]
	public function set_coupon_label( string $label, \WC_Coupon $coupon ): string {
		if ( static::get_coupon_code() !== $coupon->get_code() ) {
			return $label;
		}
		return $coupon->get_description();
	}

	#[Add_Filter( 'woocommerce_cart_totals_coupon_html' )]
	public function set_coupon_html( string $coupon_html, \WC_Coupon $coupon ): string {
		if ( static::get_coupon_code() !== $coupon->get_code() ) {
			return $coupon_html;
		}
		$amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code() );
		$coupon_html = '-' . wc_price( $amount );
		return $coupon_html;
	}

	#[Add_Filter( 'woocommerce_coupon_message' )]
	public function set_coupon_message( ?string $message, int $message_code, \WC_Coupon $coupon ): ?string {
		if ( static::get_coupon_code() !== $coupon->get_code() ) {
			return $message;
		}
		return null;
	}

	#[Add_Filter( 'woocommerce_coupon_error' )]
	public function set_coupon_error( string $message, int $error_code, ?\WC_Coupon $coupon ): ?string {
		if ( null === $coupon || static::get_coupon_code() !== $coupon->get_code() ) {
			return $message;
		}
		return null;
	}

	protected function set_coupon_presence( bool $coupon_applicable, \WC_Cart $cart ) {
		$coupon_applied = $cart->has_discount( static::get_coupon_code() );
		if ( $coupon_applicable && ! $coupon_applied ) {
			$cart->apply_coupon( static::get_coupon_code() );
		} elseif ( ! $coupon_applicable && $coupon_applied ) {
			$cart->remove_coupon( static::get_coupon_code() );
		}
	}
}
