<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Properties;

/**
 * Creëert kortingscode
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 * 
 * @todo hoort dit wel bij admin en niet bij order?s
 */
class Coupon {

	/** Type korting */
	const DISCOUNT_TYPE = 'percent';

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_order_actions', [ $self, 'add_order_action'], 10, 2 );
		add_action( 'woocommerce_order_action_siw_create_coupon', [ $self, 'create_coupon'] );//TODO::
		add_action( 'woocommerce_order_status_completed', [ $self, 'create_coupon'] );
	}

	/** Voeg orderactie voor creëren kortingscode toe */
	public function add_order_action( array $actions, \WC_Order $order ) : array {
		
		if ( $order->is_paid() && empty( wc_get_coupon_id_by_code( $order->get_order_number() ) ) ) {
			$actions['siw_create_coupon'] = __( 'Creëer kortingscode', 'siw' );
		}
		return $actions;
	}

	/**
	 * Maakt kortingscode aan
	 * @todo return statement?
	 */
	function create_coupon( $order ) {

		if ( is_int( $order ) ) {
			$order = wc_get_order( $order );
		}

		if ( ! is_a( $order, \WC_Order::class ) ) {
			return;
		}

		$application_number = $order->get_order_number();

		//Check of er al een kortingscode voor deze aanmelding bestaat
		if ( 0 !== wc_get_coupon_id_by_code( $application_number ) ) {
			return;
		}

		$coupon = new \WC_Coupon();
		$coupon->set_props( [
			'code'               => $application_number,
			'discount_type'      => self::DISCOUNT_TYPE,
			'email_restrictions' => $order->get_billing_email(),
			'amount'             => Properties::DISCOUNT_SECOND_PROJECT,
			'description'        => $order->get_formatted_billing_full_name(),
			'date_expires'       => '',
			'usage_limit'        => 1,
		]);
		$coupon->save();
		$order->add_order_note( 'Kortingscode aangemaakt' );
	}
}
