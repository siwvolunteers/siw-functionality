<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor WooCommerce
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://woocommerce.com/
 */
class WooCommerce {

	/** Init */
	public static function init() {

		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			return;
		}
		$self = new self();

		add_action( 'widgets_init', [ $self, 'unregister_widgets' ], 99 );

		add_action( 'wp_dashboard_setup', [ $self, 'remove_dashboard_widgets' ] );
		add_filter( 'woocommerce_product_visibility_options', [ $self, 'remove_product_visibility_options' ] );
		add_filter( 'woocommerce_products_admin_list_table_filters', [ $self, 'remove_products_admin_list_table_filters' ] );

		// Wachtwoord-reset niet via WooCommerce maar via standaard WordPress-methode
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );

		// Verwijder extra gebruikersvelden WooCommerce
		add_filter( 'woocommerce_customer_meta_fields', '__return_empty_array' );

		// Diverse admin-features uitschakelen
		add_filter( 'woocommerce_prevent_admin_access', '__return_false' );

		add_filter( 'woocommerce_enable_admin_help_tab', '__return_false' );
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
		add_filter( 'woocommerce_show_addons_page', '__return_false' );
		add_filter( 'woocommerce_admin_get_feature_config', [ $self, 'disable_admin_features' ] );

		// Blocks style niet laden
		add_action( 'enqueue_block_assets', [ $self, 'deregister_block_style' ], PHP_INT_MAX );

		add_action( 'wp', [ $self, 'remove_theme_support' ], PHP_INT_MAX );
		add_filter( 'rocket_cache_query_strings', [ $self, 'register_query_vars' ] );

		add_filter( 'get_term', [ $self, 'filter_term_name' ], 10, 2 );

		add_filter( 'siw_social_share_post_types', [ $self, 'set_social_share_cta' ] );
		add_filter( 'siw_carousel_post_types', [ $self, 'add_carousel_post_type' ] );
		add_filter( 'siw_carousel_post_type_taxonomies', [ $self, 'add_carousel_post_type_taxonomies' ] );
		add_filter( 'siw_carousel_post_type_templates', [ $self, 'add_carousel_template' ] );
	}

	/** Verwijdert ongebruikte widgets */
	public function unregister_widgets() {
		unregister_widget( \WC_Widget_Price_Filter::class );
		unregister_widget( \WC_Widget_Product_Categories::class );
		unregister_widget( \WC_Widget_Product_Tag_Cloud::class );
		unregister_widget( \WC_Widget_Products::class );
		unregister_widget( \WC_Widget_Cart::class );
	}

	/** Verwijdert WooCommerce-blocks style */
	public function deregister_block_style() {
		wp_deregister_style( 'wc-block-style' );
	}

	/** Schakel sommige admin features uit */
	public function disable_admin_features( array $features ): array {
		$features['onboarding'] = false;
		$features['remote-free-extensions'] = false;
		return $features;
	}

	/** Verwijdert dashboard widgets */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	/** Verwijdert overbodige zichtbaarheidsopties */
	public function remove_product_visibility_options( array $visibility_options ): array {
		unset( $visibility_options['catalog'] );
		unset( $visibility_options['search'] );
		return $visibility_options;
	}

	/** Verwijdert filters op admin-lijst met producten  */
	public function remove_products_admin_list_table_filters( array $filters ): array {
		unset( $filters['product_type'] );
		unset( $filters['stock_status'] );
		return $filters;
	}

	/** Registreert query vars voor WP Rocket TODO: naar archive*/
	public function register_query_vars( array $vars ): array {
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

	/** Zet naam van terms */
	public function filter_term_name( \WP_Term $term, string $taxonomy ): \WP_Term {
		if ( Taxonomy_Attribute::MONTH()->value === $taxonomy ) {
			$order = get_term_meta( $term->term_id, 'order', true );

			if ( empty( $order ) ) {
				return $term;
			}

			$year = substr( $order, 0, 4 );
			$month = substr( $order, 4, 2 );
			$current_year = gmdate( 'Y' );

			$term->name = ucfirst(
				siw_format_month(
					"{$year}-{$month}-1",
					$year !== $current_year
				)
			);
		}
		return $term;
	}

	/** Zet call to action voor social share links */
	public function set_social_share_cta( array $post_types ): array {
		$post_types['product'] = __( 'Deel dit project', 'siw' );
		return $post_types;
	}

	/** Voegt post type toe aan carousel */
	public function add_carousel_post_type( array $post_types ): array {
		$post_types['product'] = __( 'Groepsprojecten', 'siw' );
		return $post_types;
	}

	/** Voegt taxonomies toe aan carousel */
	public function add_carousel_post_type_taxonomies( array $taxonomies ): array {
		$taxonomies['product'] = [
			'product_cat' => __( 'Continent', 'siw' ),
		];
		return $taxonomies;
	}

	/** Voegt template toe aan carousel */
	public function add_carousel_template( array $templates ): array {
		$templates['product'] = wc_locate_template( 'content-product.php' );
		return $templates;
	}
}
