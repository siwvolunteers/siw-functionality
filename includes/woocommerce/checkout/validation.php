<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Util;

/**
 * Validatie tijdens checkout van Groepsprojecten
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Validation{

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_after_checkout_validation', [ $self, 'validate_checkout_fields' ], 10, 2 );
	}

	/** Voert validatie voor extra checkout velden uit */
	public function validate_checkout_fields( array $data, \WP_Error $errors ) {

		$dob = $data['billing_dob'];
		if ( ! (bool) date_parse( $dob ) ) {
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
