<?php

namespace SIW\WooCommerce\Checkout;

use SIW\Elements;

/**
 * Voorwaarden tijdens checkout
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Terms{

	/**
	 * Link naar modal
	 *
	 * @var string
	 */
	protected $modal_link;

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', [ $self, 'set_term_checkbox_text'] );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
		add_action( 'woocommerce_checkout_init', [ $self, 'add_terms_modal'] );
		add_filter( 'wc_get_template', [ $self, 'set_terms_template'], 10, 5 );
		add_action( 'wp_enqueue_scripts', [ $self, 'add_script'] );
	}

	/**
	 * Past link naar voorwaarden-modal aan
	 *
	 * @return string
	 */
	public function set_term_checkbox_text() {
		return sprintf(__( 'Ik heb de %s gelezen en ga akkoord', 'siw' ), $this->modal_link );
	}

	/**
	 * Voegt voorwaarden-modal toe
	 */
	public function add_terms_modal() {
		$terms_page_id = wc_terms_and_conditions_page_id();
		$this->modal_link = Elements::generate_page_modal( $terms_page_id, __( 'inschrijfvoorwaarden', 'siw' ) );
	}
	
	/**
	 * Voegt script voor voorwaarden toe
	 */
	public function add_script() {
		wp_register_script( 'siw-checkout-terms', SIW_ASSETS_URL . 'js/siw-checkout-terms.js', [ 'siw-modal' ], SIW_PLUGIN_VERSION, true );
		if ( is_checkout() ) {
			wp_enqueue_script( 'siw-checkout-terms' );
		}
	}

	/**
	 * Overschrijft templates
	 *
	 * @param string $located
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @return string
	 */
	public function set_terms_template( string $located, string $template_name, array $args, string $template_path, string $default_path ) {
		if ( 'checkout/terms.php' === $template_name ) {
			$located = SIW_TEMPLATES_DIR . '/woocommerce/'. $template_name;
		}
		return $located;
	}
}

