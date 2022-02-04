<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

/**
 * Aanpassingen aan overzichtspagina van groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */
class Archive {

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'woocommerce_after_shop_loop_item_title', [ $self, 'show_dates'] );
		add_action( 'woocommerce_after_shop_loop_item', [ $self, 'show_project_code'], 1 );

		add_action( 'woocommerce_before_shop_loop_item_title', [ $self, 'show_featured_badge' ], 10 );
	}

	/** Toont datums */
	public function show_dates() {
		global $product;
		$duration = siw_format_date_range( $product->get_attribute('startdatum'), $product->get_attribute('einddatum'), false );
		echo wpautop( esc_html( $duration ) );
	}

	/** Toont projectcode */
	public function show_project_code() {
		global $product;
		echo '<hr>';
		echo '<span class="project-code">' . esc_html( $product->get_sku() ) . '</span>';
	}

	/** Toont badge voor aanbevolen projecten */
	public function show_featured_badge() {
		global $product;
		if ( ! \is_shop() && ! \is_product_category() && ! \is_product_taxonomy() ) {
			return;
		}
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}
}
