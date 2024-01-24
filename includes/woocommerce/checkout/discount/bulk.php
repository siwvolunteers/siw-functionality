<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout\Discount;

use SIW\Attributes\Add_Action;
use SIW\Config;

class Bulk extends Virtual_Coupon {

	private const SESSION_VARIABLE = 'bulk_discount_product_ids';

	/** {@inheritDoc} */
	protected static function get_coupon_code(): string {
		return 'korting-meerdere-projecten';
	}

	/** {@inheritDoc} */
	protected static function get_discount_type(): string {
		return 'percent';
	}

	/** {@inheritDoc} */
	protected static function get_description(): string {
		return 'Korting meerdere projecten';
	}

	/** {@inheritDoc} */
	protected function get_coupon_data(): array {
		return [
			'product_ids' => WC()?->session?->get( static::SESSION_VARIABLE ) ?? [],
		];
	}

	/** {@inheritDoc} */
	protected static function get_amount(): float {
		return Config::get_discount_percentage_second_project();
	}

	#[Add_Action( 'woocommerce_before_calculate_totals' )]
	public function maybe_set_bulk_discount( \WC_Cart $cart ) {

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( $cart->get_cart_contents_count() < 2 ) {
			return;
		}

		$cart_contents = $cart->get_cart_contents();

		// Projecten oplopend sorteren op kosten
		uasort( $cart_contents, fn( array $line_1, array $line_2 ) => (float) ( $line_1['line_total'] ?? 0.0 ) <=> (float) ( $line_2['line_total'] ?? 0.0 ) );

		// Laatste element verwijderen
		$cart_contents = array_slice( $cart_contents, 0, -1, true );
		$bulk_discount_product_ids = array_values( array_column( $cart_contents, 'product_id' ) );

		WC()?->session?->set( static::SESSION_VARIABLE, $bulk_discount_product_ids );
		$this->set_coupon_presence( ! empty( $bulk_discount_product_ids ), $cart );
	}
}
