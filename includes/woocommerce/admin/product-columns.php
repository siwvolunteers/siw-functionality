<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

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
				printf( '<span class="dashicons %s"></span>', $product->is_visible() ? 'dashicons-visibility' : 'dashicons-hidden' );

				if ( $product->get_meta( 'force_hide' ) ) {
					echo '<span class="dashicons dashicons-lock"></span>';
				}

				break;
			case 'next_update':
				$product = wc_get_product( $post_id );
				if ( $product->get_meta( 'import_again' ) ) {
					echo '<span class="dashicons dashicons-update"></span>';
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

				echo sprintf (
					'<a href="%s" aria-label="%s"><span class="carousel tips %s" data-tip="%s">%s</span></a>',
					esc_url( $url ),
					esc_attr__( 'Selecteren voor carousel', 'siw' ),
					$product->get_meta( 'selected_for_carousel' ) ? 'show' : '',
					$product->get_meta( 'selected_for_carousel' ) ? esc_attr__( 'Ja', 'siw' ) : esc_attr__( 'Nee', 'siw' ),
					$product->get_meta( 'selected_for_carousel' ) ? esc_html__( 'Ja', 'siw' ) : esc_html__( 'Nee', 'siw' ),
				);
		}
	}
}
