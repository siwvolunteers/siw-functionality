<?php

namespace SIW\WooCommerce\Checkout;

use SIW\Util;

/**
 * Validatie tijdens checkout van Groepsprojecten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Validation{

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'add_validation_script'] );
		add_action( 'woocommerce_after_checkout_validation', [ $self, 'validate_checkout_fields' ], 10, 2 );
	}

	/**
	 * Voegt extra client-side validatie toe
	 * 
	 * - Postcode
	 * - Datum
	 */
	public function add_validation_script() {

		wp_register_script( 'siw-checkout-validation', SIW_ASSETS_URL . 'js/siw-checkout-validation.js', ['jquery-validate'], SIW_PLUGIN_VERSION, true );
		$validation = [
			[
				'class'   => 'dateNL',
				'regex'   => Util::get_pattern( 'date' ),
				'message' => esc_html__( 'Dit is geen geldige datum.', 'siw' ),
			],
			[
				'class'   => 'postalcodeNL',
				'regex'   => Util::get_pattern( 'postal_code' ),
				'message' => esc_html__( 'Dit is geen geldige postcode.', 'siw' ),
			],
		];

		wp_localize_script( 'siw-checkout-validation', 'siw_checkout_validation', $validation );
		if ( is_checkout() ) {
			wp_enqueue_script( 'siw-checkout-validation' );
		}
	}

	/**
	 * Voert validatie voor extra checkout velden uit
	 *
	 * @param array $data
	 * @param \WP_Error $errors
	 * 
	 * @todo minimum/maximum-leeftijd van project gebruiken
	 */
	public function validate_checkout_fields( array $data, \WP_Error $errors ) {

		$dob = $data['billing_dob'];
		if ( ! (bool) preg_match( Util::get_regex('date'), $dob ) ) {
			$errors->add( 'validation', sprintf( __( '%s bevat geen geldige datum.', 'siw' ), '<strong>' . esc_html__( 'Geboortedatum','siw' ) . '</strong>' ) );
		}
		else {
			$min_age = 14; //TODO: property / projecteigenschap
			$age = Util::calculate_age( $dob );
			if ( $age < $min_age ) {
				$errors->add( 'validation', sprintf( __( 'De minimumleeftijd voor deelname is %s jaar.', 'siw' ), '<strong>' . esc_html( $min_age ) . '</strong>' ) );
			}
		}
	}
}
