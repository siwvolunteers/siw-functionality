<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Facades\WooCommerce;
use SIW\Jobs\Async\Export_Plato_Application;

class Order_Columns extends \MBAC\Post {

	private const COLUMN_PROJECTS = 'projects';
	private const COLUMN_EXPORTED = 'exported';

	/**
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = parent::columns( $columns );
		$this->add( $columns, self::COLUMN_PROJECTS, __( 'Projecten', 'siw' ), 'after', 'order_total' );
		$this->add( $columns, self::COLUMN_EXPORTED, __( 'Export naar PLATO', 'siw' ), 'after', 'projects' );
		unset( $columns['billing_address'] );
		unset( $columns['shipping_address'] );
		return $columns;
	}

	/**
	 * @param string $column
	 * @param int    $post_id
	 */
	public function show( $column, $post_id ) {
		switch ( $column ) {

			case self::COLUMN_PROJECTS:
				$order = WooCommerce::get_order( $post_id );

				/** @var \WC_Order_Item_Product[] */
				$order_items = $order->get_items();

				foreach ( $order_items as $order_item ) {
					// TODO: SKU gebruiken indien beschikbaar
					echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $order_item->get_product_id() . '&action=edit' ) ) . '">' . esc_html( $order_item->get_name() ) . '</a><br />';
				}
				break;

			case self::COLUMN_EXPORTED:
				$order = WooCommerce::get_order( $post_id );
				$exported = $order->get_meta( Export_Plato_Application::ORDER_META_EXPORTED_TO_PLATO );
				if ( Export_Plato_Application::SUCCESS === $exported ) {
					$dashicon = 'yes';
				} elseif ( Export_Plato_Application::FAILED === $exported ) {
					$dashicon = 'no';
				} else {
					$dashicon = 'minus';
				}
				printf( '<span class="dashicons dashicons-%s"></span>', esc_attr( $dashicon ) );
				break;
		}
	}
}
