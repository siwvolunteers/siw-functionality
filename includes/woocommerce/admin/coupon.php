<?php

namespace SIW\WooCommerce\Admin;

use SIW\Properties;

/**
 * Creëert kortingscode
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @todo hoort dit wel bij admin en niet bij order?s
 */
class Coupon {

	/**
	 * Type korting
	 */
	const DISCOUNT_TYPE = 'percent';

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_order_actions', [ $self, 'add_order_action'] );
		add_action( 'woocommerce_order_action_siw_create_coupon', [ $self, 'create_coupon'] );//TODO::
		add_action( 'woocommerce_order_status_completed', [ $self, 'create_coupon'] );
	}
	/**
	 * Voeg orderactie voor creëren kortingscode toe
	 *
	 * @param array $actions
	 * @return array
	 */
	public function add_order_action( array $actions ) {
		global $theorder;
		if ( $theorder->is_paid() && empty( wc_get_coupon_id_by_code( $theorder->get_order_number() ) ) ) {
			$actions['siw_create_coupon'] = __( 'Creëer kortingscode', 'siw' );
		}
		return $actions;
	}

	/**
	 * Maakt kortingscode aan
	 *
	 * @param int|\WC_Order $order_id
	 * 
	 * @todo return statement?
	 */
	function create_coupon( $order ) {

		if ( is_int( $order ) ) {
			$order = new \WC_Order( $order );
		}

		if ( ! is_a( $order, '\WC_Order' ) ) {
			return;
		}

		$application_number = $order->get_order_number();

		//Check of er al een kortingscode voor deze aanmelding bestaat TODO: eventueel kortingscode bijwerken
		if ( 0 !== wc_get_coupon_id_by_code( $application_number ) ) {
			return;
		}

		$coupon = new \WC_Coupon();
		$coupon->set_props( [
			'code'               => $application_number,
			'discount_type'      => self::DISCOUNT_TYPE,
			'email_restrictions' => $order->get_billing_email(),
			'amount'             => empty( $order->get_coupon_codes() ) ? Properties::DISCOUNT_SECOND_PROJECT : Properties::DISCOUNT_THIRD_PROJECT,
			'description'        => $order->get_formatted_billing_full_name(),
			'date_expires'       => '',
			'usage_limit'        => 1,
		]);
		$coupon->save();
		$order->add_order_note( 'Kortingscode aangemaakt' );
	}
}
