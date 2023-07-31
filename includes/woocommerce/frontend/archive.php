<?php declare(strict_types=1);

namespace SIW\WooCommerce\Frontend;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Aanpassingen aan overzichtspagina van groepsprojecten
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */
class Archive extends Base {

	#[Action( 'woocommerce_after_shop_loop_item_title' )]
	public function show_project_data() {
		global $post;
		$product = siw_get_product( $post );
		if ( null === $product ) {
			return;
		}

		// TODO: vlag en icons voor datum/soort-werk
		echo wp_kses_post(
			sprintf(
				'<p>%s<br/>%s<br/>%s</p>',
				$product->get_country()->get_name(),
				implode( ' | ', wc_get_product_terms( $product->get_id(), Taxonomy_Attribute::WORK_TYPE()->value, [ 'fields' => 'names' ] ) ),
				siw_format_date_range( $product->get_start_date(), $product->get_end_date(), false )
			)
		);
	}

	#[Action( 'woocommerce_before_shop_loop_item_title' )]
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
