<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Formatting;

/**
 * Aanpassingen voor WooCommerce
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://woocommerce.com/
 * @since     3.0.0
 */
class WooCommerce {

	/**
	 * Aantal log items per pagina
	 * 
	 * @var int
	 */
	const LOG_ITEMS_PER_PAGE = 25;

	/**
	 * Aantal dagen dat log bewaard wordt
	 * 
	 * @var int
	 */
	const DAYS_TO_RETAIN_LOG = 7;

	/**
	 * Init
	 */
	public static function init() {

		if ( ! class_exists( '\WooCommerce' ) ) {
			return;
		}
		$self = new self();

		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], 99 );

		$self->set_log_handler();
		add_filter( 'woocommerce_register_log_handlers', [ $self, 'register_log_handlers' ], PHP_INT_MAX );
		add_filter( 'woocommerce_status_log_items_per_page', [ $self, 'set_log_items_per_page' ] );
		add_filter( 'woocommerce_logger_days_to_retain_logs', [ $self, 'set_days_to_retain_log'] );
		add_filter( 'nonce_user_logged_out', [ $self, 'reset_nonce_user_logged_out' ], PHP_INT_MAX, 2 );
		add_action( 'wp_dashboard_setup', [ $self, 'remove_dashboard_widgets' ] );
		add_filter( 'product_type_selector', [ $self, 'disable_product_types'] );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ $self, 'enable_project_id_search' ], 10, 2 );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ $self, 'enable_country_search' ], 10, 2 );
		add_filter( 'woocommerce_product_visibility_options', [ $self, 'remove_product_visibility_options', ] );
		add_filter( 'woocommerce_products_admin_list_table_filters', [ $self, 'remove_products_admin_list_table_filters'] );

		/* Wachtwoord-reset niet via WooCommerce maar via standaard WordPress-methode */
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );

		/* WooCommerce filter kortsluiten: iedereen die mag inloggen mag het dashboard zien */
		add_filter( 'woocommerce_prevent_admin_access', '__return_false' );

		/* Verwijder extra gebruikersvelden WooCommerce */
		add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );

		add_filter( 'wc_session_use_secure_cookie', '__return_true' );
		add_filter( 'woocommerce_enable_admin_help_tab', '__return_false' );
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
		add_filter( 'woocommerce_show_addons_page', '__return_false' );

		add_filter( 'woocommerce_admin_disabled', '__return_true' );
		add_filter( 'woocommerce_marketing_menu_items', '__return_empty_array' );
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

		//Blocks style niet laden
		add_action( 'enqueue_block_assets', [ $self, 'deregister_block_style' ], PHP_INT_MAX );

		add_action( 'wp', [ $self, 'remove_theme_support'], PHP_INT_MAX );
		add_filter('woocommerce_single_product_image_thumbnail_html', [ $self, 'remove_link_on_thumbnails'] );

		add_filter( 'woocommerce_layered_nav_count', '__return_empty_string' );
		add_filter( 'rocket_cache_query_strings', [ $self, 'register_query_vars'] );

		add_filter( 'get_term', [ $self, 'filter_term_name'], 10, 2 );
	}

	/**
	 * Verwijdert ongebruikte widgets
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
	 */
	public function set_log_handler() {
		define( 'WC_LOG_HANDLER', 'WC_Log_Handler_DB' );
	}

	/**
	 * Verwijdert WooCommerce-blocks style
	 */
	public function deregister_block_style() {
		wp_deregister_style( 'wc-block-style' );
	}

	/**
	 * Registreert log handlers
	 * 
	 * - Database
	 * - E-mail (voor hoge prioriteit)
	 *
	 * @return array
	 */
	public function register_log_handlers() {
		$log_handler_db = new \WC_Log_Handler_DB;
		$log_handler_email = new \WC_Log_Handler_Email;
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
	 * @return int
	 */
	public function set_log_items_per_page() : int {
		return self::LOG_ITEMS_PER_PAGE;
	}

	/**
	 * Zet aantal dagen dat log bewaard wordt
	 *
	 * @return int
	 */
	public function set_days_to_retain_log() : int {
		return self::DAYS_TO_RETAIN_LOG;
	}

	/**
	 * Maakt het aanpassen van nonce voor logged-out user door WooCommerce ongedaan
	 *
	 * @param string $user_id
	 * @param string $action
	 * @return string
	 */
	public function reset_nonce_user_logged_out( $user_id, string $action ) {
		$nonces = [
			'wp_rest',
		];
		if ( class_exists( '\WooCommerce' ) ) {
			if ( $user_id && 0 !== $user_id && $action && ( in_array( $action, $nonces ) ) ) {
				$user_id = 0;
			}
		}
		return $user_id;
	}

	/**
	 * Verwijdert dashboard widgets
	 */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	/**
	 * Schakelt ongebruikte product types uit 
	 *
	 * @param array $product_types
	 * @return array
	 */
	public function disable_product_types( array $product_types ) : array {
		unset( $product_types['simple'] );
		unset( $product_types['grouped'] );
		unset( $product_types['external'] );
		return $product_types;
	}

	/**
	 * Voegt project_id als argument toe aan WC queries
	 *
	 * @param array $query
	 * @param array $query_vars
	 * @return array
	 */
	public function enable_project_id_search( array $query, array $query_vars ) {
		if ( ! empty( $query_vars['project_id'] ) ) {
			$query['meta_query'][] = [
				'key'   => 'project_id',
				'value' => esc_attr( $query_vars['project_id'] ),
			];
		}
		return $query;
	}
	
	/**
	 * Voegt country argument toe aan WC queries
	 *
	 * @param array $query
	 * @param array $query_vars
	 * @return array
	 */
	public function enable_country_search( array $query, array $query_vars ) {
		if ( ! empty( $query_vars['country'] ) ) {
			$query['meta_query'][] = [
				'key'   => 'country',
				'value' => esc_attr( $query_vars['country'] ),
			];
		}
		return $query;
	}

	/**
	 * Verwijdert overbodige zichtbaarheidsopties
	 *
	 * @param array $visibility_options
	 * @return array
	 */
	public function remove_product_visibility_options( array $visibility_options ) : array {
		unset( $visibility_options['catalog']);
		unset( $visibility_options['search']);
		return $visibility_options;
	}

	/**
	 * Verwijdert filters op admin-lijst met producten 
	 *
	 * @param array $filters
	 * @param array
	 */
	public function remove_products_admin_list_table_filters( array $filters ) : array {
		unset( $filters['product_type']);
		unset( $filters['stock_status']);
		return $filters;
	}

	/**
	 * Registreert query vars voor WP Rocket
	 *
	 * @param array $vars
	 * @return array
	 */
	public function register_query_vars( array $vars ) : array {
		$taxonomies = wc_get_attribute_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$vars[] = "filter_{$taxonomy->attribute_name}";
		}
		return $vars;
	}

	/**
	 * Verwijdert theme support
	 * 
	 * - Zoom
	 * - Lightbox
	 * - Slider
	 */
	public function remove_theme_support() {
		remove_theme_support( 'wc-product-gallery-zoom' );
		remove_theme_support( 'wc-product-gallery-lightbox' );
		remove_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Verwijdert link bij productafbeelding
	 *
	 * @param string $html
	 *
	 * @return string
	 */
	public function remove_link_on_thumbnails( string $html ) : string {
		return strip_tags( $html, '<img>' );
	}

	/**
	 * Zet naam van terms
	 *
	 * @param \WP_Term $term
	 * @param string $taxonomy
	 *
	 * @return \WP_Term
	 */
	public function filter_term_name( \WP_Term $term, string $taxonomy ) : \WP_Term {
		if ( 'pa_maand' == $taxonomy ) {
			$order = get_term_meta( $term->term_id, 'order', true );
			$year = substr( $order, 0, 4 );
			$month = substr( $order, 4, 2 );
			$current_year = date( 'Y' );

			$term->name = ucfirst(
				Formatting::format_month(
					"{$year}-{$month}-1",
					$year != $current_year
				)
			); 
		}
		return $term;
	}
}
