<?php

namespace SIW\WooCommerce\Admin;

/**
 * Extra admin columns voor Groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Product_Columns extends \MB_Admin_Columns_Post {

	/**
	 * Voegt extra columns toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns  = parent::columns( $columns );
		$this->add( $columns, 'visibility', __( 'Zichtbaarheid', 'siw' ), 'after', 'sku' );
		$this->add( $columns, 'next_update', __( 'Volgende update', 'siw' ), 'after', 'featured' );
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
				$visibility = $product->is_visible();
	
				if ( $visibility ) {
					$dashicon = 'visibility';
				}
				else {
					$dashicon = 'hidden';
				}
				printf( '<span class="dashicons dashicons-%s"></span>', $dashicon );
				break;
			case 'next_update':
				$product = wc_get_product( $post_id );
				if ( true == $product->get_meta( 'import_again' ) ) {
					echo '<span class="dashicons dashicons-update"></span>';
				}
				if ( 'hide' == $product->get_meta( 'manual_visibility' ) && $product->is_visible() ) {
					echo '<span class="dashicons dashicons-hidden"></span>';
				}
				break;
		}
	}
}
