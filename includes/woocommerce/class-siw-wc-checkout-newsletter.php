<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanmelding voor nieuwsbrief tijdens WooCommerce checkout
 * 
 * @package    SIW\WooCommerce
 * @author     Maarten Bruna
 * @copyright  2018 SIW Internationale Vrijwilligersprojecten
 * */
class SIW_WC_Checkout_Newsletter{

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'woocommerce_after_checkout_billing_form', [ $self, 'show_newsletter_signup_checkbox'] );
		add_filter( 'woocommerce_checkout_posted_data', [ $self, 'capture_newsletter_signup'] );
		add_action( 'woocommerce_checkout_order_processed', [ $self, 'process_newsletter_signup'], 10, 3 );
	}

	/**
	 * Toont checkbox voor aanmelden nieuwsbrief
	 *
	 * @param WC_Checkout $checkout
	 */
	public function show_newsletter_signup_checkbox( $checkout ) {
		woocommerce_form_field( 'newsletter_signup', [
			'type'  => 'checkbox',
			'class' => ['form-row-wide'],
			'clear' => true,
			'label' => __( 'Ja, ik wil graag de SIW nieuwsbrief ontvangen', 'siw' ),
			], $checkout->get_value( 'newsletter_signup' )
		);
	}

	/**
	 * Voegt aanmelding voor nieuwsbrief toe aan posted data
	 *
	 * @param array $data
	 * @return array
	 */
	public function capture_newsletter_signup( $data ) {
		$data['newsletter_signup'] = (int) isset( $_POST['newsletter_signup'] );
		return $data;
	}

	/**
	 * Verwerkt aanmelding voor nieuwsbrief
	 *
	 * @param int $order_id
	 * @param array $posted_data
	 * @param WC_Order $order
	 */
	public function process_newsletter_signup( $order_id, $posted_data, $order ) {
		if ( ! class_exists( 'WYSIJA' ) ) {
			return;
		}

		$list = (int) siw_get_setting( 'newsletter_list' );
		if ( 1 == $posted_data['newsletter_signup'] ) {
			$user_data = [
				'email'     => $order->get_billing_email(),
				'firstname' => $order->get_billing_first_name(),
				'lastname'  => $order->get_billing_last_name(),
			];
			$data_subscriber = [
				'user'      => $user_data,
				'user_list' => [ 'list_ids' => [ $list ] ]
			];
			$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber, true );
		}
	}
}