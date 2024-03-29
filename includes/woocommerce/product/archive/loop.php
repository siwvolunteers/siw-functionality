<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Archive;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Facades\WooCommerce;
use SIW\WooCommerce\Taxonomy_Attribute;

class Loop extends Base {

	#[Add_Action( 'woocommerce_after_shop_loop_item_title' )]
	public function show_project_data() {
		global $post;
		$product = WooCommerce::get_product( $post );
		if ( null === $product ) {
			return;
		}

		// TODO: vlag en icons voor datum/soort-werk
		echo wp_kses_post(
			sprintf(
				'<p>%s<br/>%s<br/>%s</p>',
				$product->get_country()->label(),
				implode( ' | ', WooCommerce::get_product_terms( $product->get_id(), Taxonomy_Attribute::WORK_TYPE->value, [ 'fields' => 'names' ] ) ),
				siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false )
			)
		);
	}

	#[Add_Action( 'woocommerce_before_shop_loop_item_title' )]
	public function show_featured_badge() {
		global $post;
		$product = WooCommerce::get_product( $post );
		if ( null === $product ) {
			return;
		}
		if ( ! WooCommerce::is_shop() && ! WooCommerce::is_product_category() && ! WooCommerce::is_product_taxonomy() ) {
			return;
		}
		if ( $product->is_featured() && ! $product->is_on_sale() ) {
			echo '<span class="product-badge featured-badge">' . esc_html__( 'Aanbevolen', 'siw' ) . '</span>';
		}
	}
}
