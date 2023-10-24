<?php declare(strict_types=1);

namespace SIW\Compatibility;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Features\Social_Share;
use SIW\Interfaces\Compatibility\Plugin as I_Plugin;
use SIW\Widgets\Carousel;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen voor WooCommerce
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 * @see       https://woocommerce.com/
 */
class WooCommerce extends Base implements I_Plugin {

	#[Add_Filter( 'woocommerce_customer_meta_fields' )]
	private const CUSTOMER_META_FIELDS = [];

	#[Add_Filter( 'woocommerce_prevent_admin_access' )]
	private const PREVENT_ADMIN_ACCESS = false;

	#[Add_Filter( 'woocommerce_disable_admin_bar' )]
	private const DISABLE_ADMIN_BAR = false;

	#[Add_Filter( 'woocommerce_enable_admin_help_tab' )]
	private const ENABLE_ADMIN_HELP_TAB = false;

	#[Add_Filter( 'woocommerce_show_addons_page' )]
	private const SHOW_ADDONS_PAGE = false;

	public const PRODUCT_POST_TYPE = 'product';

	/** {@inheritDoc} */
	public static function get_plugin_basename(): string {
		return 'woocommerce/woocommerce.php';
	}

	#[Add_Action( 'woocommerce_before_customer_object_save' )]
	public function fix_billing_country( \WC_Customer $customer ) {
		// Tijdelijke fix, kan weg als https://github.com/woocommerce/woocommerce/pull/37463 is opgeleverd
		$customer->set_billing_country( null );
	}

	#[Add_Filter( 'lostpassword_url', 1 )]
	/** Lost password via wp methode */
	public function remove_lostpassword_url_filter( string $lostpassword_url ): string {
		remove_filter( 'lostpassword_url', 'wc_lostpassword_url', 10 );
		return $lostpassword_url;
	}

	#[Add_Action( 'widgets_init', 99 )]
	/** Verwijdert ongebruikte widgets */
	public function unregister_widgets() {
		unregister_widget( \WC_Widget_Price_Filter::class );
		unregister_widget( \WC_Widget_Product_Categories::class );
		unregister_widget( \WC_Widget_Product_Tag_Cloud::class );
		unregister_widget( \WC_Widget_Products::class );
		unregister_widget( \WC_Widget_Cart::class );
	}

	#[Add_Action( 'enqueue_block_assets', PHP_INT_MAX )]
	/** Verwijdert WooCommerce-blocks style */
	public function deregister_block_style() {
		wp_deregister_style( 'wc-blocks-style' );
	}

	#[Add_Filter( 'woocommerce_admin_get_feature_config' )]
	/** Schakel sommige admin features uit */
	public function disable_admin_features( array $features ): array {
		$features['onboarding'] = false;
		$features['remote-free-extensions'] = false;
		return $features;
	}

	#[Add_Action( 'wp_dashboard_setup' )]
	/** Verwijdert dashboard widgets */
	public function remove_dashboard_widgets() {
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	#[Add_Filter( 'woocommerce_product_visibility_options' )]
	/** Verwijdert overbodige zichtbaarheidsopties */
	public function remove_product_visibility_options( array $visibility_options ): array {
		unset( $visibility_options['catalog'] );
		unset( $visibility_options['search'] );
		return $visibility_options;
	}


	#[Add_Filter( 'woocommerce_products_admin_list_table_filters' )]
	/** Verwijdert filters op admin-lijst met producten  */
	public function remove_products_admin_list_table_filters( array $filters ): array {
		unset( $filters['product_type'] );
		unset( $filters['stock_status'] );
		return $filters;
	}


	#[Add_Filter( 'rocket_cache_query_strings' )]
	/** Registreert query vars voor WP Rocket TODO: naar archive*/
	public function register_query_vars( array $vars ): array {
		$taxonomies = wc_get_attribute_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$vars[] = "filter_{$taxonomy->attribute_name}";
		}
		return $vars;
	}

	#[Add_Action( 'wp', PHP_INT_MAX )]
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

	#[Add_Filter( 'get_term' )]
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

	#[Add_Action( 'init' )]
	public function add_post_type_support() {
		add_post_type_support(
			'product',
			Social_Share::POST_TYPE_FEATURE,
			[
				'cta' => __( 'Deel dit project', 'siw' ),
			]
		);
		add_post_type_support( self::PRODUCT_POST_TYPE, Carousel::POST_TYPE_FEATURE );
	}

	#[Add_Action( 'init' )]
	public function remove_product_tag() {
		unregister_taxonomy_for_object_type( 'product_tag', self::PRODUCT_POST_TYPE );
	}

	#[Add_Action( 'init', 20 )]
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

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_cart_fragment_script() {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}
