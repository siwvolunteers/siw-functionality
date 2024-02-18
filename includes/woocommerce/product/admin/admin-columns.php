<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Admin;

use SIW\Facades\WooCommerce;
use SIW\WooCommerce\Product\WC_Product_Project;

class Admin_Columns extends \MBAC\Post {

	private const COLUMN_COUNTRY = 'country';
	private const COLUMN_START_DATE = 'start_date';
	private const COLUMN_VISIBILITY = 'visibility';

	/**
	 * Voegt extra columns toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = parent::columns( $columns );
		$this->add( $columns, self::COLUMN_COUNTRY, __( 'Land', 'siw' ), 'after', 'sku' );
		$this->add( $columns, self::COLUMN_START_DATE, __( 'Startdatum', 'siw' ), 'after', 'product_cat' );
		$this->add( $columns, self::COLUMN_VISIBILITY, __( 'Zichtbaarheid', 'siw' ), 'after', 'start_date' );
		unset( $columns['thumb'] );
		unset( $columns['date'] );
		unset( $columns['product_tag'] );
		unset( $columns['price'] );
		return $columns;
	}

	/**
	 * Toont extra columns
	 *
	 * @param string $column
	 * @param int    $post_id
	 */
	public function show( $column, $post_id ) {
		switch ( $column ) {
			case self::COLUMN_VISIBILITY:
				$product = $this->get_product( $post_id );
				if ( null === $product ) {
					return;
				}
				printf( '<span class="dashicons %s"></span>', $product->is_visible() ? 'dashicons-visibility' : 'dashicons-hidden' );

				if ( $product->is_hidden() ) {
					echo '<span class="dashicons dashicons-lock"></span>';
				}
				break;
			case self::COLUMN_START_DATE:
				$product = $this->get_product( $post_id );
				if ( null === $product ) {
					return;
				}
				echo esc_html( $product->get_start_date() );
				break;
			case self::COLUMN_COUNTRY:
				$product = $this->get_product( $post_id );
				if ( null === $product ) {
					return;
				}
				echo esc_html( $product->get_country()->label() );
				break;
		}
	}

	protected function get_product( int $post_id ): ?WC_Product_Project {
		$product = wp_cache_get( $post_id, __METHOD__ );
		if ( false !== $product ) {
			return $product;
		}
		$product = WooCommerce::get_product( $post_id );
		wp_cache_set( $post_id, $product, __METHOD__ );

		return $product;
	}
}
