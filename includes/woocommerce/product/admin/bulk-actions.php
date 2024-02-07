<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Admin;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Facades\WooCommerce;
use SIW\Jobs\Async\Import_Plato_Project;
use SIW\WooCommerce\Product\WC_Product_Project;

class Bulk_Actions extends Base {

	// Constantes
	private const ACTION_IMPORT_AGAIN = 'import_again';
	private const ACTION_MARK_AS_FEATURED = 'mark_as_featured';
	private const ACTION_HIDE = 'hide';
	private const QUERY_ARG_ACTION = 'siw-action';
	private const QUERY_ARG_COUNT = 'siw-count';

	#[Add_Filter( 'bulk_actions-edit-product' )]
	public function add_bulk_actions( array $bulk_actions ): array {
		$bulk_actions[ self::ACTION_IMPORT_AGAIN ] = __( 'Opnieuw importeren', 'siw' );
		$bulk_actions[ self::ACTION_MARK_AS_FEATURED ] = __( 'Markeren als aanbevolen', 'siw' );
		$bulk_actions[ self::ACTION_HIDE ] = __( 'Verbergen', 'siw' );
		return $bulk_actions;
	}

	#[Add_Filter( 'handle_bulk_actions-edit-product' )]
	public function handle_bulk_actions( string $redirect_url, string $action, array $post_ids ): string {

		switch ( $action ) {
			case self::ACTION_IMPORT_AGAIN:
				$products = WooCommerce::get_products( [ 'include' => $post_ids ] );
				array_walk(
					$products,
					function ( WC_Product_Project $product ) {
						$data = [
							'product_id' => $product->get_project_id(),
						];
						as_enqueue_async_action( Import_Plato_Project::class, $data );
					}
				);
				break;
			case self::ACTION_HIDE:
				$products = WooCommerce::get_products( [ 'include' => $post_ids ] );
				array_walk(
					$products,
					function ( WC_Product_Project $product ) {
						$product->set_hidden( true );
						$product->set_catalog_visibility( 'hidden' );
						$product->save();
					}
				);
				break;
			case self::ACTION_MARK_AS_FEATURED:
				$products = WooCommerce::get_products( [ 'include' => $post_ids ] );
				array_walk(
					$products,
					function ( WC_Product_Project $product ) {
						$product->set_featured( true );
						$product->save();
					}
				);
				break;
			default:
				// Afbreken
				return $redirect_url;
		}

		return add_query_arg(
			[
				self::QUERY_ARG_ACTION => $action,
				self::QUERY_ARG_COUNT  => count( $post_ids ),
			],
			$redirect_url
		);
	}

	#[Add_Action( 'admin_notices' )]
	public function show_admin_notice() {

		$action = get_query_arg( self::QUERY_ARG_ACTION );
		$count = (int) get_query_arg( self::QUERY_ARG_COUNT );

		switch ( $action ) {
			case self::ACTION_IMPORT_AGAIN:
				// translators: %d is het aantal projecten
				$message = sprintf( _n( '%d project wordt opnieuw geïmporteerd.', '%d projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
				break;
			case self::ACTION_MARK_AS_FEATURED:
				// translators: %d is het aantal projecten
				$message = sprintf( _n( '%d project is gemarkeerd als aanbevolen.', '%d projecten zijn gemarkeerd als aanbevolen.', $count, 'siw' ), $count );
				break;
			case self::ACTION_HIDE:
				// translators: %d is het aantal projecten
				$message = sprintf( _n( '%d project is verborgen.', '%d projecten zijn verborgen.', $count, 'siw' ), $count );
				break;
			default:
				// Afbreken
				return;
		}

		wp_admin_notice(
			$message,
			[
				'type'        => 'info',
				'dismissable' => true,
			]
		);
	}

	#[Add_Filter( 'removable_query_args' )]
	public function add_removable_query_args( array $removable_query_args ): array {
		$removable_query_args[] = self::QUERY_ARG_ACTION;
		$removable_query_args[] = self::QUERY_ARG_COUNT;
		return $removable_query_args;
	}
}
