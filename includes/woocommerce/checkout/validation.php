<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Util;
use SIW\WooCommerce\Product\WC_Product_Project;

/**
 * Validatie tijdens checkout van Groepsprojecten
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Validation {

	const MIN_AGE = 14;
	const MAX_AGE = 99;

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_after_checkout_validation', [ $self, 'validate_checkout_fields' ], 10, 2 );
	}

	/** Voert validatie voor extra checkout velden uit */
	public function validate_checkout_fields( array $data, \WP_Error $errors ) {

		$has_required_field_error = false;
		foreach ( $errors->get_error_codes() as $code ) {
			if ( str_ends_with( $code, '_required' ) ) {
				$has_required_field_error = true;
				$errors->remove( $code );
			}
		}

		if ( true === $has_required_field_error ) {
			$errors->add( 'validation', __( 'Vul de verplichte velden in.', 'siw' ) );
		}

		$dob = $data['billing_dob'];
		if ( ! (bool) date_parse( $dob ) ) {
			// translators: %s is 'Geboortedatum'
			$errors->add( 'validation', sprintf( __( '%s bevat geen geldige datum.', 'siw' ), '<strong>' . esc_html__( 'Geboortedatum', 'siw' ) . '</strong>' ) );
		} else {
			$age = Util::calculate_age( $dob );

			foreach ( WC()->cart->get_cart() as $cart_item ) {
				/** @var WC_Product_Project */
				$product = $cart_item['data'];

				$min_age = max( self::MIN_AGE, $product->get_min_age() );
				$max_age = min( self::MAX_AGE, $product->get_max_age() );

				if ( $age < $min_age ) {
					$errors->add(
						'validation',
						sprintf(
							// translators: %1$s is de projectnaam, %2$s een geheel getal
							__( 'De minimumleeftijd voor deelname aan het project %1$s is %2$s jaar.', 'siw' ),
							'<strong>' . esc_html( $product->get_name() ) . '</strong>',
							'<strong>' . esc_html( $min_age ) . '</strong>'
						)
					);
				} elseif ( $age > $max_age ) {
					$errors->add(
						'validation',
						sprintf(
							// translators: %1$s is de projectnaam, %2$s een geheel getal
							__( 'De maximumleeftijd voor deelname aan het project %1$s is %2$s jaar.', 'siw' ),
							'<strong>' . esc_html( $product->get_name() ) . '</strong>',
							'<strong>' . esc_html( $max_age ) . '</strong>'
						)
					);
				}
			}
		}
	}
}
