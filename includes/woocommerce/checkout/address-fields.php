<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Add_Filter;
use SIW\Base;

/**
 * Adresvelden in WooCommerce checkout
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Address_Fields extends Base {

	#[Add_Filter( 'woocommerce_shipping_fields' )]
	private const SHIPPING_FIELDS = [];

	#[Add_Filter( 'woocommerce_default_address_fields' )]
	public function set_default_address_fields( array $default_address_fields ): array {

		/* Verwijderen standaardvelden */
		unset( $default_address_fields['address_1'] );
		unset( $default_address_fields['address_2'] );
		unset( $default_address_fields['postcode'] );
		unset( $default_address_fields['city'] );
		unset( $default_address_fields['company'] );
		unset( $default_address_fields['state'] );
		unset( $default_address_fields['country'] );

		/* Reset alle classes */
		$default_address_fields = array_map(
			function ( $field ) {
				$field['class'] = array_diff( $field['class'], [ 'form-row-first', 'form-row-last', 'form-row-wide' ] );
				return $field;
			},
			$default_address_fields
		);

		$address_fields = siw_get_data( 'workcamps/address-fields' );
		$address_fields = wp_parse_args_recursive( $address_fields, $default_address_fields );

		return $address_fields;
	}

	#[Add_Filter( 'woocommerce_billing_fields' )]
	public function set_billing_fields( array $billing_fields, string $country ): array {
		$billing_fields['billing_phone']['class'] = [ 'form-row-first' ];
		$billing_fields['billing_email']['class'] = [ 'form-row-last' ];
		return $billing_fields;
	}
}
