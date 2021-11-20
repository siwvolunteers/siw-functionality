<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

/**
 * Bulk acties
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Bulk_Actions {

	//Constantes
	const ACTION_IMPORT_AGAIN = 'import_again';
	const ACTION_MARK_AS_FEATURED = 'mark_as_featured';
	const ACTION_HIDE = 'hide';
	const QUERY_ARG_ACTION = 'siw-action';
	const QUERY_ARG_COUNT = 'siw-count';

	/** Init */
	public static function init() {
		$self = new self();
		add_filter( 'bulk_actions-edit-product', [ $self, 'add_bulk_actions'] );
		add_filter( 'handle_bulk_actions-edit-product', [ $self, 'handle_bulk_actions'], 10, 3 );
		add_action( 'admin_notices', [ $self, 'show_admin_notice'] );
	}

	/**
	 * Voegt bulk acties toe
	 * 
	 * - Opnieuw importeren
	 * - Selecteren voor carousel
	 */
	public function add_bulk_actions( array $bulk_actions ): array {
		$bulk_actions[ self::ACTION_IMPORT_AGAIN ] = __( 'Opnieuw importeren', 'siw' );
		$bulk_actions[ self::ACTION_MARK_AS_FEATURED ] = __( 'Markeren als aanbevolen', 'siw' );
		$bulk_actions[ self::ACTION_HIDE ] = __( 'Verbergen', 'siw' );
		return $bulk_actions;
	}

	/** Verwerkt bulkacties */
	public function handle_bulk_actions( string $redirect_url, string $action, array $post_ids ): string {
		
		switch ( $action ) {
			case self::ACTION_IMPORT_AGAIN:
				$products = siw_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$data = [
							'product_id' => $product->get_meta( 'project_id' )
						];
						siw_enqueue_async_action( 'import_plato_project', $data );
					}
				);
				break;
			case self::ACTION_HIDE:
				$products = siw_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$product->update_meta_data( 'force_hide', true );
						$product->set_catalog_visibility( 'hidden' );
						$product->save();
					}
				);
				break;
			case self::ACTION_MARK_AS_FEATURED:
				$products = siw_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$product->set_featured( true );
						$product->save();
					}
				);
				break;
			default:
				//Afbreken
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

	/** Toon admin notice */
	public function show_admin_notice() {

		$action = get_query_arg( self::QUERY_ARG_ACTION );
		$count = (int) get_query_arg( self::QUERY_ARG_COUNT );

		switch ( $action ) {
			case self::ACTION_IMPORT_AGAIN:
				$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
				break;
			case self::ACTION_MARK_AS_FEATURED:
				$message = sprintf( _n( '%s project is gemarkeerd als aanbevolen.', '%s projecten zijn gemarkeerd als aanbevolen.', $count, 'siw' ), $count );
				break;
			case self::ACTION_HIDE:
				$message = sprintf( _n( '%s project is verborgen.', '%s projecten zijn verborgen.', $count, 'siw' ), $count );
				break;
			default:
				//Afbreken
				return;
		}

		//TODO: admin element van maken?
		?>
			<div class="notice notice-info is-dismissible">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
		<?php
	}
}
