<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout\Discount;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;

/**
 * Basis klasse voor een virtuele coupon
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 */
abstract class Virtual_Coupon extends Base {

	/** Geeft coupont code terug */
	abstract protected static function get_coupon_code(): string;

	/**
	 * Geeft type korting terug
	 *
	 * Mogelijke waardes:
	 * - fixed_cart
	 * - fixed_product
	 * - percent
	 */
	abstract protected static function get_discount_type(): string;

	/** Geeft beschrijving van de korting terug */
	abstract protected static function get_description(): string;

	/** Geeft bedrag of percentage korting terug */
	abstract protected static function get_amount(): float;

	#[Filter( 'woocommerce_get_shop_coupon_data' )]
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

	/** Geeft extra coupon data terug (te overschrijven door subclasses) */
	protected function get_coupon_data(): array {
		return [];
	}

	#[Filter( 'woocommerce_cart_totals_coupon_label' )]
	public function set_coupon_label( string $label, \WC_Coupon $coupon ): string {
		if ( static::get_coupon_code() !== $coupon->get_code() ) {
			return $label;
		}
		return $coupon->get_description();
	}

	#[Filter( 'woocommerce_cart_totals_coupon_html' )]
	public function set_coupon_html( string $coupon_html, \WC_Coupon $coupon ): string {
		if ( static::get_coupon_code() !== $coupon->get_code() ) {
			return $coupon_html;
		}
		$amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code() );
		$coupon_html = '-' . wc_price( $amount );
		return $coupon_html;
	}

	/** Onderdruk message bij toevoegen verwijderen coupon */
	#[Filter( 'woocommerce_coupon_message' )]
	public function set_coupon_message( ?string $message, int $message_code, \WC_Coupon $coupon ): ?string {
		if ( static::get_coupon_code() !== $coupon->get_code() ) {
			return $message;
		}
		return null;
	}

	/** Voegt of verwijdert coupon indien nodig */
	protected function set_coupon_presence( bool $coupon_applicable, \WC_Cart $cart ) {
		$coupon_applied = $cart->has_discount( static::get_coupon_code() );
		if ( $coupon_applicable && ! $coupon_applied ) {
			$cart->apply_coupon( static::get_coupon_code() );
		} elseif ( ! $coupon_applicable && $coupon_applied ) {
			$cart->remove_coupon( static::get_coupon_code() );
		}
	}

}
