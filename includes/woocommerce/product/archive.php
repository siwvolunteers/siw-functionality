<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

/**
 * TODO:
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Archive {

	/** Init */
	public static function init() {
		$self = new self();
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 ); //FIXME: Werkt niet; hook te vroeg
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		add_action( 'woocommerce_before_shop_loop_item_title', [ $self, 'show_featured_badge' ], 10 );

		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_country'] );
		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_dates'] );

		
		add_action( 'woocommerce_after_shop_loop_item', [ $self, 'show_project_code'], 1 );
	}


	/** Toont badge voor aanbevolen projecten */
	public function show_featured_badge() {
		global $product;
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}

	/** Toont het land */
	public function show_country() {
		global $post;
		$product = \siw_get_product( $post );
		if ( null == $product ) {
			return;
		}
		echo wpautop( esc_html( $product->get_country()->get_name() ) );
	}

	/** Toont datums */
	public function show_dates() {
		global $post;
		$product = \siw_get_product( $post );
		if ( null == $product ) {
			return;
		}
		$duration = \siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false );
		echo wpautop( esc_html( $duration ) );
	}
	
	/** Toont projectcode */
	public function show_project_code() {
		global $post;
		$product = \siw_get_product( $post );
		if ( null == $product ) {
			return;
		}
		echo '<hr>';
		echo '<span class="project-code">' . esc_html( $product->get_sku() ) . '</span>';
	}
}