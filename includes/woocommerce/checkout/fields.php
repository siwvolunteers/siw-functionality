<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

/**
 * WooCommerce checkout
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Fields {

	/** Init */
	public static function init() {
		$self = new self();

		add_filter( 'woocommerce_default_address_fields', [ $self , 'set_default_address_fields'], 10, 2 );
		add_filter( 'woocommerce_billing_fields', [ $self, 'set_billing_fields'], 10, 2 );
		add_filter( 'woocommerce_shipping_fields', '__return_empty_array' );
		add_filter( 'woocommerce_checkout_fields', [ $self, 'add_checkout_fields'] );
		add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
		add_filter( 'woocommerce_get_country_locale', [ $self, 'remove_locale_postcode_priority'] );
		add_filter( 'woocommerce_country_locale_field_selectors', [ $self, 'remove_locale_field_selectors']);
		add_action( 'woocommerce_checkout_create_order', [ $self, 'save_checkout_fields'], 10, 2 );
	}

	/** Verwijdert JS-selectors voor update locale */
	public function remove_locale_field_selectors( array $locale_fields ) : array {
		unset( $locale_fields['address_2'] );
		unset( $locale_fields['state'] );
		return $locale_fields;
	}

	/** Verwijdert aangepaste prioriteit voor postcode */
	public function remove_locale_postcode_priority( array $locale ) : array {
		unset( $locale['NL']['postcode'] );
		return $locale;
	}
	
	/** Haalt checkoutvelden op */
	protected function get_checkout_fields( array $checkout_fields = [] ) : array {
		$checkout_fields = wp_parse_args_recursive( siw_get_data( 'workcamps/checkout-fields' ), $checkout_fields );
		return $checkout_fields;
	}

	/** Slaat de extra checkoutvelden op */
	public function save_checkout_fields( \WC_Order $order, array $data ) {
		
		$checkout_fields = $this->get_checkout_fields();

		foreach ( $checkout_fields as $section => $fields ) {
			foreach ( $fields as $key => $field ) {
				if ( isset( $data[ $key ] ) ) {
					$order->update_meta_data( $key, $data[ $key ] );
				}
			}
		}
		if ( ! empty( $data['terms'] ) ) {
			$order->update_meta_data( '_terms', $data['terms'] );
		}
	}
	
	/** Past de volgorde van de adresvelden aan */
	public function set_default_address_fields( array $standard_address_fields ) : array {

		/* Verwijderen standaardvelden */
		unset( $standard_address_fields['address_2'] );
		unset( $standard_address_fields['company'] );
		unset( $standard_address_fields['state'] );

		/* Reset alle classes */
		$standard_address_fields = array_map( function( $field ) {
			unset( $field['class']);
			return $field;
		}, $standard_address_fields);

		$address_fields = siw_get_data( 'workcamps/address-fields' );
		$address_fields = wp_parse_args_recursive( $address_fields, $standard_address_fields );

		return $address_fields;
	}

	/** Zet de classes voor de billing velden */
	public function set_billing_fields( array $billing_fields, string $country ) : array {
		$billing_fields['billing_phone']['class'] = ['form-row-first'];
		$billing_fields['billing_email']['class'] = ['form-row-last'];
		return $billing_fields;
	}

	/** Voegt de extra checkoutvelden toe */
	public function add_checkout_fields( $checkout_fields ) : array {
		$checkout_fields = $this->get_checkout_fields( $checkout_fields );
		return $checkout_fields;
	}
}
