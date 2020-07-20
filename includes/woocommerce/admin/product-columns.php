<?php

namespace SIW\WooCommerce\Admin;

use SIW\HTML;

/**
 * Extra admin columns voor Groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
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
		$this->add( $columns, 'selected_for_carousel', __( 'Selecteren voor carousel', 'siw' ), 'after', 'featured' );
		$this->add( $columns, 'next_update', __( 'Volgende update', 'siw' ), 'after', 'selected_for_carousel' );
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
				echo HTML::span(
					[
						'class' => $product->is_visible() ? 'dashicons dashicons-visibility' : 'dashicons dashicons-hidden',
					]
				);
				if ( $product->get_meta( 'force_hide' ) ) {
					echo HTML::span(
						[
							'class' => 'dashicons dashicons-lock',
						]
					);
					
				}

				break;
			case 'next_update':
				$product = wc_get_product( $post_id );
				if ( $product->get_meta( 'import_again' ) ) {
					echo HTML::span( ['class' => 'dashicons dashicons-update'] ) ;
				}
				break;
			case 'selected_for_carousel':
				$product = wc_get_product( $post_id );

				$url = add_query_arg(
					[
						'action'     => 'woocommerce_select_for_carousel',
						'product_id' => $product->get_id()
					],
					'admin-ajax.php'
				);
				$url = wp_nonce_url( admin_url( $url ), 'woocommerce-select-for-carousel' );

				echo HTML::a(
					[
						'href'       => $url,
						'aria-label' => __( 'Selecteren voor carousel', 'siw' ),
					],
					HTML::span(
						[
							'class'    => $product->get_meta( 'selected_for_carousel' ) ? 'carousel show tips' : 'carousel tips',
							'data-tip' => $product->get_meta( 'selected_for_carousel' ) ? __( 'Yes', 'woocommerce' ) : __( 'No', 'woocommerce' ),
						],
						$product->get_meta( 'selected_for_carousel' ) ? __( 'Yes', 'woocommerce' ) : __( 'No', 'woocommerce' )
					)
				);
		}
	}
}
