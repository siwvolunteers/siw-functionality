<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\Admin\Notices as Admin_Notices;

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
		$bulk_actions['mark_as_featured'] = __( 'Markeren als aanbevolen', 'siw' );
		$bulk_actions['force_hide'] = __( 'Verbergen', 'siw' );
		return $bulk_actions;
	}

	/** Verwerkt bulkacties */
	public function handle_bulk_actions( string $redirect_to, string $action, array $post_ids ): string {
		$count = count( $post_ids );
		switch ( $action ) {
			case 'import_again':
				$products = wc_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$data = [
							'product_id' => $product->get_meta( 'project_id' )
						];
						siw_enqueue_async_action( 'import_plato_project', $data );
					}
				);
				$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
				break;
			case 'force_hide':
				$products = wc_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$product->update_meta_data( 'force_hide', true );
						$product->set_catalog_visibility( 'hidden' );
						$product->save();
					}
				);
				$message = sprintf( _n( '%s project is verborgen.', '%s projecten zijn verborgen.', $count, 'siw' ), $count );
				break;
			case 'mark_as_featured':
				$products = wc_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$product->set_featured( true );
						$product->save();
					}
				);
				$message = sprintf( _n( '%s project is gemarkeerd als aanbevolen.', '%s projecten zijn gemarkeerd als aanbevolen.', $count, 'siw' ), $count );
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
