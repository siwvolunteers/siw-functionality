<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

/**
 * Postcode lookup in WC Checkout
 * 
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
class Postcode_Lookup {

	const ASSETS_HANDLE = 'siw-checkout-postcode-lookup';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'add_postcode_script' ] );
	}

	/** Voegt inline script voor postcode lookup toe */
	public function add_postcode_script() {

		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/siw-checkout-postcode-lookup.js', ['siw-api-postcode-lookup'], SIW_PLUGIN_VERSION, true );

		$postcode_selectors = [
			'postcode'    => "billing_postcode",
			'housenumber' => "billing_housenumber",
			'street'      => "billing_address_1",
			'city'        => "billing_city",
		];
		wp_localize_script( self::ASSETS_HANDLE, 'siw_checkout_postcode_selectors', $postcode_selectors );

		if ( is_checkout() && ! is_order_received_page() && ! is_checkout_pay_page() ) {
			wp_enqueue_script( self::ASSETS_HANDLE );
		}
	}

}
