<?php declare(strict_types=1);
namespace SIW\WooCommerce\Checkout;

use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Gender;
use SIW\Data\Nationality;

class Address_Fields extends Base {

	#[Add_Filter( 'woocommerce_shipping_fields' )]
	private const SHIPPING_FIELDS = [];

	#[Add_Filter( 'woocommerce_default_address_fields' )]
	public function set_default_address_fields( array $default_address_fields ): array {

		unset( $default_address_fields['address_1'] );
		unset( $default_address_fields['address_2'] );
		unset( $default_address_fields['postcode'] );
		unset( $default_address_fields['city'] );
		unset( $default_address_fields['company'] );
		unset( $default_address_fields['state'] );
		unset( $default_address_fields['country'] );

		$default_address_fields = array_map(
			function ( $field ) {
				$field['class'] = array_diff( $field['class'], [ 'form-row-first', 'form-row-last', 'form-row-wide' ] );
				return $field;
			},
			$default_address_fields
		);

		return wp_parse_args_recursive( $this->get_address_fields(), $default_address_fields );
	}

	#[Add_Filter( 'woocommerce_billing_fields' )]
	public function set_billing_fields( array $billing_fields, string $country ): array {
		$billing_fields['billing_phone']['class'] = [ 'form-row-first' ];
		$billing_fields['billing_email']['class'] = [ 'form-row-last' ];
		return $billing_fields;
	}

	public function get_address_fields(): array {
		return [
			'first_name'  => [
				'class'    => [ 'form-row-first' ],
				'priority' => 10,
			],
			'last_name'   => [
				'class'    => [ 'form-row-last' ],
				'priority' => 20,
			],
			'dob'         => [
				'label'    => __( 'Geboortedatum', 'siw' ),
				'required' => true,
				'type'     => 'date',
				'class'    => [ 'form-row-first', 'update_totals_on_change' ],
				'priority' => 30,
			],
			'nationality' => [
				'label'    => __( 'Nationaliteit', 'siw' ),
				'required' => true,
				'type'     => 'select',
				'options'  => Nationality::list(),
				'default'  => Nationality::HOL->value,
				'class'    => [ 'form-row-last' ],
				'priority' => 40,
			],
			'gender'      => [
				'label'    => __( 'Geslacht', 'siw' ),
				'required' => true,
				'type'     => 'radio',
				'options'  => Gender::list(),
				'class'    => [ 'form-row-first' ],
				'priority' => 50,
			],
			'student'     => [
				'label'    => __( 'Ben je student?', 'siw' ),
				'type'     => 'radio',
				'class'    => [ 'form-row-last', 'update_totals_on_change' ],
				'options'  => [
					'yes' => __( 'Ja', 'siw' ),
					'no'  => __( 'Nee', 'siw' ),
				],
				'default'  => 'no',
				'priority' => 60,
			],

		];
	}
}
