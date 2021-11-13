<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

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
		$this->add( $columns, 'visibility', __( 'Zichtbaarheid', 'siw' ), 'after', 'sku' );
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
				$product = wc_get_product( $post_id );
				printf( '<span class="dashicons %s"></span>', $product->is_visible() ? 'dashicons-visibility' : 'dashicons-hidden' );

				if ( $product->get_meta( 'force_hide' ) ) {
					echo '<span class="dashicons dashicons-lock"></span>';
				}
				break;
		}
	}
}
