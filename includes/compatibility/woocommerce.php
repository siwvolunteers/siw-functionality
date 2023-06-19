<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor WooCommerce
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://woocommerce.com/
 */
class WooCommerce extends Base implements I_Plugin {

	#[Filter( 'woocommerce_customer_meta_fields' )]
	private const CUSTOMER_META_FIELDS = [];

	#[Filter( 'woocommerce_prevent_admin_access' )]
	private const PREVENT_ADMIN_ACCESS = false;

	#[Filter( 'woocommerce_enable_admin_help_tab' )]
	private const ENABLE_ADMIN_HELP_TAB = false;

	#[Filter( 'woocommerce_show_addons_page' )]
	private const SHOW_ADDONS_PAGE = false;

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'woocommerce/woocommerce.php';
	}

	#[Action( 'woocommerce_before_customer_object_save' )]
	public function fix_billing_country( \WC_Customer $customer ) {
		// Tijdelijke fix, kan weg als https://github.com/woocommerce/woocommerce/pull/37463 is opgeleverd
		$customer->set_billing_country( null );
	}

	#[Filter( 'lostpassword_url', 1 )]
	/** Lost password via wp methode */
	public function remove_lostpassword_url_filter() {
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );
	}

	#[Action( 'widgets_init', 99 )]
	/** Verwijdert ongebruikte widgets */
	public function unregister_widgets() {
		unregister_widget( \WC_Widget_Price_Filter::class );
		unregister_widget( \WC_Widget_Product_Categories::class );
		unregister_widget( \WC_Widget_Product_Tag_Cloud::class );
		unregister_widget( \WC_Widget_Products::class );
		unregister_widget( \WC_Widget_Cart::class );
	}

	#[Action( 'enqueue_block_assets', PHP_INT_MAX )]
	/** Verwijdert WooCommerce-blocks style */
	public function deregister_block_style() {
		wp_deregister_style( 'wc-blocks-style' );
	}

	#[Filter( 'woocommerce_admin_get_feature_config' )]
	/** Schakel sommige admin features uit */
	public function disable_admin_features( array $features ): array {
		$features['onboarding'] = false;
		$features['remote-free-extensions'] = false;
		return $features;
	}

	#[Action( 'wp_dashboard_setup' )]
	/** Verwijdert dashboard widgets */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	#[Filter( 'woocommerce_product_visibility_options' )]
	/** Verwijdert overbodige zichtbaarheidsopties */
	public function remove_product_visibility_options( array $visibility_options ): array {
		unset( $visibility_options['catalog'] );
		unset( $visibility_options['search'] );
		return $visibility_options;
	}


	#[Filter( 'woocommerce_products_admin_list_table_filters' )]
	/** Verwijdert filters op admin-lijst met producten  */
	public function remove_products_admin_list_table_filters( array $filters ): array {
		unset( $filters['product_type'] );
		unset( $filters['stock_status'] );
		return $filters;
	}


	#[Filter( 'rocket_cache_query_strings' )]
	/** Registreert query vars voor WP Rocket TODO: naar archive*/
	public function register_query_vars( array $vars ): array {
		$taxonomies = wc_get_attribute_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$vars[] = "filter_{$taxonomy->attribute_name}";
		}
		return $vars;
	}

	#[Action( 'wp', PHP_INT_MAX )]
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

	#[Filter( 'get_term' )]
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

	#[Filter( 'siw_social_share_post_types' )]
	/** Zet call to action voor social share links */
	public function set_social_share_cta( array $post_types ): array {
		$post_types['product'] = __( 'Deel dit project', 'siw' );
		return $post_types;
	}

	#[Filter( 'siw_carousel_post_types' )]
	/** Voegt post type toe aan carousel */
	public function add_carousel_post_type( array $post_types ): array {
		$post_types['product'] = __( 'Groepsprojecten', 'siw' );
		return $post_types;
	}

	#[Filter( 'siw_carousel_post_type_taxonomies' )]
	/** Voegt taxonomies toe aan carousel */
	public function add_carousel_post_type_taxonomies( array $taxonomies ): array {
		$taxonomies['product'] = [
			'product_cat' => __( 'Continent', 'siw' ),
		];
		return $taxonomies;
	}

	#[Filter( 'siw_carousel_post_type_templates' )]
	/** Voegt template toe aan carousel */
	public function add_carousel_template( array $templates ): array {
		$templates['product'] = wc_locate_template( 'content-product.php' );
		return $templates;
	}

	#[Action( 'init', 20 )]
	/**
	 * Verwijdert legacy image sizes van WooCommerce
	 *
	 * @see https://github.com/woocommerce/woocommerce/issues/28139
	 */
	public function remove_legacy_image_sizes() {
		remove_image_size( 'shop_catalog' );
		remove_image_size( 'shop_single' );
		remove_image_size( 'shop_thumbnail' );
	}

	#[Action( 'wp_enqueue_scripts' )]
	public function enqueue_cart_fragment_script() {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
