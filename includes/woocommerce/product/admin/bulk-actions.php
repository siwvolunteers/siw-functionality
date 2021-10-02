<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Admin;

use SIW\Admin\Notices as Admin_Notices;
use SIW\WooCommerce\WC_Product_Project;

/**
 * Bulk acties
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Bulk_Actions {

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'bulk_actions-edit-product', [ $self, 'add_bulk_actions'] );
		add_filter( 'handle_bulk_actions-edit-product', [ $self, 'handle_bulk_actions'], 10, 3 );
	}

	/**
	 * Voegt bulk acties toe
	 * 
	 * - Opnieuw importeren
	 * - Selecteren voor carousel
	 */
	public function add_bulk_actions( array $bulk_actions ): array {
		$bulk_actions['import_again'] = __( 'Opnieuw importeren', 'siw' );
		$bulk_actions['select_for_carousel'] = __( 'Selecteren voor carousel', 'siw' );
		$bulk_actions['force_hide'] = __( 'Verbergen', 'siw' );
		return $bulk_actions;
	}

	/** Verwerkt bulkacties */
	public function handle_bulk_actions( string $redirect_to, string $action, array $post_ids ) : string {
		$count = count( $post_ids );
		switch ( $action ) {
			case 'import_again':
				$products = siw_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( WC_Product_Project $product ) {
						$data = [
							'product_id' => $product->get_project_id(),
						];
						siw_enqueue_async_action( 'import_plato_project', $data );
					}
				);
				$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
				break;
			case 'select_for_carousel':
				$products = siw_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( WC_Product_Project $product ) {
						$product->set_selected_for_carousel( true );
						$product->save();
					}
				);
				$message = sprintf( _n( '%s project is geselecteerd voor de carousel.', '%s projecten zijn geselecteerd voor de carousel.', $count, 'siw' ), $count );
				break;
			case 'force_hide':
				$products = siw_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( WC_Product_Project $product ) {
						$product->set_hidden( true );
						$product->set_catalog_visibility( 'hidden' );
						$product->save();
					}
				);
				$message = sprintf( _n( '%s project is verborgen.', '%s projecten zijn verborgen.', $count, 'siw' ), $count );
				break;
			default:
		}

		if ( isset( $message ) ) {
			$notices = new Admin_Notices;
			$notices->add_notice( 'info', $message , true);
		}

		return $redirect_to;
	}
}
