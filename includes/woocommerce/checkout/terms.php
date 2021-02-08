<?php declare(strict_types=1);

namespace SIW\WooCommerce\Checkout;

use SIW\Elements;

/**
 * Voorwaarden tijdens checkout
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Terms{

	/** Link naar modal */
	protected string $modal_link;

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', [ $self, 'set_term_checkbox_text'] );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
		add_action( 'woocommerce_checkout_init', [ $self, 'add_terms_modal'] );
		add_action( 'wp_enqueue_scripts', [ $self, 'add_script'] );
	}

	/** Past link naar voorwaarden-modal aan */
	public function set_term_checkbox_text() : string {
		return sprintf(__( 'Ik heb de %s gelezen en ga akkoord', 'siw' ), $this->modal_link );
	}

	/** Voegt voorwaarden-modal toe */
	public function add_terms_modal() {
		$terms_page_id = wc_terms_and_conditions_page_id();
		$this->modal_link = Elements::generate_page_modal( $terms_page_id, __( 'inschrijfvoorwaarden', 'siw' ) );
	}
	
	/** Voegt script voor voorwaarden toe */
	public function add_script() {
		wp_register_script( 'siw-checkout-terms', SIW_ASSETS_URL . 'js/siw-checkout-terms.js', [ 'siw-modal' ], SIW_PLUGIN_VERSION, true );
		if ( is_checkout() && ! is_order_received_page() && ! is_checkout_pay_page() ) {
			wp_enqueue_script( 'siw-checkout-terms' );
		}
	}
}

