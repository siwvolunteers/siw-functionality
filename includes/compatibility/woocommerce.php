<?php declare(strict_types=1);

namespace SIW\Compatibility;

/**
 * Aanpassingen voor WooCommerce
 * 
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://woocommerce.com/
 */
class WooCommerce {

	/** Aantal log items per pagina */
	const LOG_ITEMS_PER_PAGE = 25;

	/** Aantal dagen dat log bewaard wordt */
	const DAYS_TO_RETAIN_LOG = 7;

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		$self = new self();

		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], 99 );

		$self->set_log_handler();
		add_filter( 'woocommerce_register_log_handlers', [ $self, 'register_log_handlers' ], PHP_INT_MAX );
		add_filter( 'woocommerce_status_log_items_per_page', fn() : int => self::LOG_ITEMS_PER_PAGE );
		add_filter( 'woocommerce_logger_days_to_retain_logs', fn() : int => self::DAYS_TO_RETAIN_LOG );
		add_filter( 'nonce_user_logged_out', [ $self, 'reset_nonce_user_logged_out' ], PHP_INT_MAX, 2 );
		add_action( 'wp_dashboard_setup', [ $self, 'remove_dashboard_widgets' ] );
		add_filter( 'product_type_selector', [ $self, 'disable_product_types'] );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ $self, 'enable_project_id_search' ], 10, 2 );
		add_filter( 'woocommerce_product_data_store_cpt_get_products_query', [ $self, 'enable_country_search' ], 10, 2 );
		add_filter( 'woocommerce_product_visibility_options', [ $self, 'remove_product_visibility_options', ] );
		add_filter( 'woocommerce_products_admin_list_table_filters', [ $self, 'remove_products_admin_list_table_filters'] );

		// Wachtwoord-reset niet via WooCommerce maar via standaard WordPress-methode
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );

		// Verwijder extra gebruikersvelden WooCommerce
		add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );
		
		//Diverse admin-features uitschakelen
		add_filter( 'woocommerce_prevent_admin_access', '__return_false' );

		add_filter( 'woocommerce_enable_admin_help_tab', '__return_false' );
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
		add_filter( 'woocommerce_show_addons_page', '__return_false' );
		add_filter( 'woocommerce_admin_disabled', '__return_true' );

		//Blocks style niet laden
		add_action( 'enqueue_block_assets', [ $self, 'deregister_block_style' ], PHP_INT_MAX );

		add_action( 'wp', [ $self, 'remove_theme_support'], PHP_INT_MAX );
		add_filter('woocommerce_single_product_image_thumbnail_html', [ $self, 'remove_link_on_thumbnails'] );

		add_filter( 'woocommerce_layered_nav_count', '__return_empty_string' );
		add_filter( 'rocket_cache_query_strings', [ $self, 'register_query_vars'] );

		add_filter( 'get_term', [ $self, 'filter_term_name'], 10, 2 );

		add_filter( 'siw_update_woocommerce_terms_taxonomies', [ $self, 'set_update_terms_taxonomies'] );
		add_filter( 'siw_update_woocommerce_terms_delete_empty', [ $self, 'set_update_terms_delete_empty'], 10, 2 );

		add_filter( 'siw_social_share_post_types', [ $self, 'set_social_share_cta'] );
		add_filter( 'siw_carousel_post_types', [ $self, 'add_carousel_post_type' ] );
		add_filter( 'siw_carousel_post_type_taxonomies', [ $self, 'add_carousel_post_type_taxonomies' ] );
		add_filter( 'siw_carousel_post_type_templates', [ $self, 'add_carousel_template' ] );
	}

	/** Verwijdert ongebruikte widgets */
	public function unregister_widgets() {
		unregister_widget( 'WC_Widget_Price_Filter' );
		unregister_widget( 'WC_Widget_Product_Categories' );
		unregister_widget( 'WC_Widget_Product_Tag_Cloud' );
		unregister_widget( 'WC_Widget_Products' );
		unregister_widget( 'WC_Widget_Cart' );
	}

	/** Zet database als de standaard log handler */
	public function set_log_handler() {
		define( 'WC_LOG_HANDLER', 'WC_Log_Handler_DB' );
	}

	/** Verwijdert WooCommerce-blocks style */
	public function deregister_block_style() {
		wp_deregister_style( 'wc-block-style' );
	}

	/**
	 * Registreert log handlers
	 * 
	 * - Database
	 * - E-mail (voor hoge prioriteit)
	 */
	public function register_log_handlers() : array {
		$log_handler_db = new \WC_Log_Handler_DB;
		$log_handler_email = new \WC_Log_Handler_Email;
		$log_handler_email->set_threshold( 'alert' );
	
		$handlers = [
			$log_handler_db,
			$log_handler_email,
		];
	
		return $handlers;
	}

	/** Maakt het aanpassen van nonce voor logged-out user door WooCommerce ongedaan */
	public function reset_nonce_user_logged_out( $user_id, string $action ) {
		$nonces = [
			'wp_rest',
		];
		if ( $user_id && 0 !== $user_id && $action && ( in_array( $action, $nonces ) ) ) {
			$user_id = get_current_user_id();;
		}
		return $user_id;
	}

	/** Verwijdert dashboard widgets */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	/** Schakelt ongebruikte product types uit */
	public function disable_product_types( array $product_types ) : array {
		unset( $product_types['simple'] );
		unset( $product_types['grouped'] );
		unset( $product_types['external'] );
		return $product_types;
	}

	/** Voegt project_id als argument toe aan WC queries */
	public function enable_project_id_search( array $query, array $query_vars ) {
		if ( ! empty( $query_vars['project_id'] ) ) {
			$query['meta_query'][] = [
				'key'   => 'project_id',
				'value' => esc_attr( $query_vars['project_id'] ),
			];
		}
		return $query;
	}
	
	/** Voegt country argument toe aan WC queries */
	public function enable_country_search( array $query, array $query_vars ) {
		if ( ! empty( $query_vars['country'] ) ) {
			$query['meta_query'][] = [
				'key'   => 'country',
				'value' => esc_attr( $query_vars['country'] ),
			];
		}
		return $query;
	}

	/** Verwijdert overbodige zichtbaarheidsopties */
	public function remove_product_visibility_options( array $visibility_options ) : array {
		unset( $visibility_options['catalog']);
		unset( $visibility_options['search']);
		return $visibility_options;
	}

	/** Verwijdert filters op admin-lijst met producten  */
	public function remove_products_admin_list_table_filters( array $filters ) : array {
		unset( $filters['product_type']);
		unset( $filters['stock_status']);
		return $filters;
	}

	/** Registreert query vars voor WP Rocket */
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

	/** Verwijdert link bij productafbeelding */
	public function remove_link_on_thumbnails( string $html ) : string {
		return strip_tags( $html, '<img>' ); //TODO: verplaatsen naar product
	}

	/** Zet naam van terms */
	public function filter_term_name( \WP_Term $term, string $taxonomy ) : \WP_Term {
		if ( 'pa_maand' == $taxonomy ) {
			$order = get_term_meta( $term->term_id, 'order', true );

			if ( empty( $order ) ) {
				return $term;
			}

			$year = substr( $order, 0, 4 );
			$month = substr( $order, 4, 2 );
			$current_year = date( 'Y' );

			$term->name = ucfirst(
				siw_format_month(
					"{$year}-{$month}-1",
					$year != $current_year
				)
			);
		}
		return $term;
	}

	/** Zet taxonomies waarvan terms bijgewerkt moet worden */
	public function set_update_terms_taxonomies( array $taxonomies ) : array {
		$taxonomies[] = 'product_cat';
		$taxonomies[] = 'pa_maand';
		$taxonomies[] = 'pa_land';
		return $taxonomies;
	}

	/** Undocumented function */
	public function set_update_terms_delete_empty( bool $delete_empty, string $taxonomy ) : bool {
		if ( 'pa_maand' == $taxonomy ) {
			$delete_empty = true;
		}
		return $delete_empty;
	}

	/** Zet call to action voor social share links */
	public function set_social_share_cta( array $post_types ) : array {
		$post_types['product'] = __( 'Deel dit project', 'siw' );
		return $post_types;
	}

	/** Voegt post type toe aan carousel */
	public function add_carousel_post_type( array $post_types ) : array {
		$post_types['product'] = __( 'Groepsprojecten', 'siw' );
		return $post_types;
	}

	/** Voegt taxonomies toe aan carousel */
	public function add_carousel_post_type_taxonomies( array $taxonomies ) : array {
		$taxonomies['product'] = [
			'product_cat' => __( 'Continent', 'siw' ),
		];
		return $taxonomies;
	}

	/** Voegt template toe aan carousel */
	public function add_carousel_template( array $templates ) : array {
		$templates['product'] = wc_locate_template( 'content-product.php' );
		return $templates;
	}



}
