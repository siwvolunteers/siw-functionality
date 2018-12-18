<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Aanpassingen voor WooCommerce
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_WooCommerce {

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$self = new self();

		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], PHP_INT_MAX );

		$self->set_log_handler();
		add_filter( 'woocommerce_register_log_handlers', [ $self, 'register_log_handlers' ], PHP_INT_MAX );
		add_filter( 'woocommerce_status_log_items_per_page', [ $self, 'set_log_items_per_page' ] );
		add_filter( 'nonce_user_logged_out', [ $self, 'reset_nonce_user_logged_out' ], PHP_INT_MAX, 2 );
		add_action( 'wp_dashboard_setup', [ $self, 'remove_dashboard_widgets' ] );

		/* Wachtwoord-reset niet via WooCommerce maar via standaard WordPress-methode */
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );

		/* WooCommerce filter kortsluiten: iedereen die mag inloggen mag het dashboard zien */
		add_filter( 'woocommerce_prevent_admin_access', '__return_false' );

		/* Verwijder extra gebruikersvelden WooCommerce */
		add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );

		/* Woocommerce cookie secure maken */
		add_filter( 'wc_session_use_secure_cookie', '__return_true' );

		/* WooCommerce help-tab verbergen*/
		add_filter( 'woocommerce_enable_admin_help_tab', '__return_false' );
	}

	/**
	 * Verwijdert ongebruikte widgets
	 *
	 * @return void
	 */
	public function unregister_widgets() {
		unregister_widget( 'WC_Widget_Price_Filter' );
		unregister_widget( 'WC_Widget_Product_Categories' );
		unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
		unregister_widget( 'WC_Widget_Products' );
		unregister_widget( 'WC_Widget_Cart' );
	}

	/**
	 * Zet database als de standaard log handler
	 *
	 * @return void
	 */
	public function set_log_handler() {
		define( 'WC_LOG_HANDLER', 'WC_Log_Handler_DB' );
	}

	/**
	 * Registreert log handlers
	 * 
	 * - Database
	 * - E-mail (voor hoge prioriteit)
	 *
	 * @param array $handlers
	 * @return array
	 */
	public function register_log_handlers( $handlers ) {
		$log_handler_db = new WC_Log_Handler_DB;
		$log_handler_email = new WC_Log_Handler_Email;
		$log_handler_email->set_threshold( 'alert' );
	
		$handlers = [
			$log_handler_db,
			$log_handler_email,
		];
	
		return $handlers;
	}

	/**
	 * Zet het aantal log items per pagina
	 *
	 * @param int $per_page
	 * @return int
	 */
	public function set_log_items_per_page( $per_page ) {
		$per_page = 25;
		return $per_page;
	}

	/**
	 * Maakt het aanpassen van nonce voor logged-out user door WooCommerce ongedaan
	 *
	 * @param string $user_id
	 * @param string $action
	 * @return void
	 */
	public function reset_nonce_user_logged_out( $user_id, $action ) {
		$nonces = [
			'siw_ajax_nonce',
			'siw_newsletter_nonce',
			'wp_rest',
		];
		if ( class_exists( 'WooCommerce' ) ) {
			if ( $user_id && 0 !== $user_id && $action && ( in_array( $action, $nonces ) ) ) {
				$user_id = 0;
			}
		}
		return $user_id;
	}

	/**
	 * Verwijdert dashboard widgets
	 *
	 * @return void
	 */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

}

