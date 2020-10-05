<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

/**
 * Extra Admin columns voor aanmeldingen
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Order_Columns extends \MBAC\Post {

	/**
	 * Voegt extra columns toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns = parent::columns( $columns );
		$this->add( $columns, 'projects', __( 'Projecten', 'siw' ), 'after', 'order_total' );
		$this->add( $columns, 'exported', __( 'Export naar PLATO', 'siw' ), 'after', 'projects' );
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

			case 'projects':
				$order = wc_get_order( $post_id );
				$order_items = $order->get_items();
		 
				foreach ( $order_items as $order_item ) {
						echo '<a href="' . admin_url('post.php?post=' . $order_item->get_product_id() . '&action=edit' ) . '">'. $order_item->get_name() .'</a><br />';
				}
				break;

			case 'exported':
				$order = wc_get_order( $post_id );
				$exported = $order->get_meta( '_exported_to_plato' ); 
				if ( 'success' == $exported  ) {
					$dashicon = 'yes';
				}
				else if ( 'failed' == $exported ) {
					$dashicon = 'no';
				}
				else {
					$dashicon = 'minus';
				}
				printf( '<span class="dashicons dashicons-%s"></span>', $dashicon );
				break;
		}
	}
}
