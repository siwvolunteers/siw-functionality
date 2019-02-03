<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extra Admin columns voor aanmeldingen
 *
 * @package   SIW\WooCommerce
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_WC_Admin_Order_Columns extends MB_Admin_Columns_Post {

	/**
	 * Voegt extra columns toe
	 *
	 * @param array $columns
	 * @return array
	 */
	public function columns( $columns ) {
		$columns  = parent::columns( $columns );
		$this->add( $columns, 'exported', __( 'Export naar PLATO', 'siw' ), 'after', 'order_total' );
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