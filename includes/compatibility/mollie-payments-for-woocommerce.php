<?php declare(strict_types=1);


namespace SIW\Compatibility;

use SIW\Attributes\Action;
use SIW\Base;

/**
 * Aanpassingen voor Mollie Payments for WooCommerce
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Mollie_Payments_For_Woocommerce extends Base {

	#[Action( 'wp_enqueue_scripts', PHP_INT_MAX )]
	public function dequeue_mollie_assets() {
		wp_dequeue_script( 'mollie_block_index' );
		wp_dequeue_style( 'mollie-gateway-icons' );
	}
}
