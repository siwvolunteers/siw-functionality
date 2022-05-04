<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

/**
 * Adresvelden in WooCommerce checkout
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Address_Fields {

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'woocommerce_default_address_fields', [ $self, 'set_default_address_fields' ], 10, 2 );
		add_filter( 'woocommerce_billing_fields', [ $self, 'set_billing_fields' ], 10, 2 );
		add_filter( 'woocommerce_shipping_fields', '__return_empty_array' );
	}

	/** Past de volgorde van de adresvelden aan */
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
			function( $field ) {
				$field['class'] = array_diff( $field['class'], [ 'form-row-first', 'form-row-last', 'form-row-wide' ] );
				return $field;
			},
			$default_address_fields
		);

		$address_fields = siw_get_data( 'workcamps/address-fields' );
		$address_fields = wp_parse_args_recursive( $address_fields, $default_address_fields );

		return $address_fields;
	}

	/** Zet de classes voor de billing velden */
	public function set_billing_fields( array $billing_fields, string $country ): array {
		$billing_fields['billing_phone']['class'] = [ 'form-row-first' ];
		$billing_fields['billing_email']['class'] = [ 'form-row-last' ];
		return $billing_fields;
	}
}
