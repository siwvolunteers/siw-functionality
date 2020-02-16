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
	 * 
	 * @todo script van maken met jquery validation als dependency + wp_localize_script voor patterns + conditie op is_checkout
	 */
	public function add_validation_script() {
		$inline_script = "$.validator.setDefaults({
			errorPlacement: function( error, element ) {
				error.appendTo( element.parents( 'p' ) );
			}
		});";

		$validator = "$.validator.addMethod( '%s', function( value, element ) {
			return this.optional( element ) || %s.test( value );
		}, '%s' );";
		
		/* Datumvalidatie */
		$inline_script .= sprintf(
			$validator,
			'dateNL',
			Util::get_regex( 'date' ),
			esc_html__( 'Dit is geen geldige datum.', 'siw' )
		);
		/* Postcodevalidatie*/
		$inline_script .= sprintf(
			$validator,
			'postalcodeNL',
			Util::get_regex( 'postal_code' ),
			esc_html__( 'Dit is geen geldige postcode.', 'siw' )
		);
		wp_add_inline_script( 'jquery-validate', "(function( $ ) {" . $inline_script . "})( jQuery );" );
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
