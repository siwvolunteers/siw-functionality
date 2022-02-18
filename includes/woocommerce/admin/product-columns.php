<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\WooCommerce\Product_Attribute;
use SIW\WooCommerce\Taxonomy_Attribute;

/**
 * Extra admin columns voor Groepsprojecten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product_Columns extends \MBAC\Post {

	/**
	 * Voegt extra columns toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = parent::columns( $columns );
		$this->add( $columns, 'country', __( 'Land', 'siw'), 'after', 'sku' );
		$this->add( $columns, 'start_date', __( 'Startdatum', 'siw'), 'after', 'product_cat' );
		$this->add( $columns, 'visibility', __( 'Zichtbaarheid', 'siw' ), 'after', 'start_date' );
		unset( $columns['thumb']);
		unset( $columns['date'] );
		unset( $columns['product_tag'] );
		unset( $columns['price'] );
		return $columns;
	}

	/**
	 * Toont extra columns
	 *
	 * @param string $column
	 * @param int $post_id
	 */
	public function show( $column, $post_id ) {
		switch ( $column ) {
			case 'visibility':
				$product = $this->get_product( $post_id );
				printf( '<span class="dashicons %s"></span>', $product->is_visible() ? 'dashicons-visibility' : 'dashicons-hidden' );

				if ( $product->get_meta( 'force_hide' ) ) {
					echo '<span class="dashicons dashicons-lock"></span>';
				}
				break;
			case 'start_date':
				$product = $this->get_product( $post_id );
				echo $product->get_attribute( Product_Attribute::START_DATE()->value );
				break;
			case 'country':
				$product = $this->get_product( $post_id );
				echo $product->get_attribute( Taxonomy_Attribute::COUNTRY()->value );
				break;
		}
	}

	/** Haalt het product op  */
	protected function get_product( int $post_id ): ?\WC_Product {
		$product = wp_cache_get( $post_id, __METHOD__ );
		if ( false !== $product ) {
			return $product;
		}
		$product = siw_get_product( $post_id );
		wp_cache_set( $post_id, $product, __METHOD__ );

		return $product;
	}
}
