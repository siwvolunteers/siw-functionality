<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product;

use SIW\WooCommerce\Import\Product as Import_Product;

/**
 * Goedkeuring van projecten
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Approval {

	/** Afgekeurd */
	const REJECTED = 'rejected';

	/** Goedgekeurd */
	const APPROVED = 'approved';

	/** Init */
	public static function init() {
		$self = new self();
		add_action( 'post_submitbox_misc_actions', [ $self, 'show_approval_result'] );
		add_action( 'post_submitbox_start', [ $self, 'show_approval_option'] );
		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_approval_result'] );
	}

	/** Toont optie om nog niet gepubliceerd project af of goed te keuren */
	public function show_approval_option( \WP_Post $post ) {
		if ( 'product' != $post->post_type || Import_Product::REVIEW_STATUS != $post->post_status ) {
			return;
		}
		$product = siw_get_product( $post->ID );
		$approval_result = $product->get_meta( 'approval_result' );
		woocommerce_wp_radio(
			[
				'id'          => '_approval_result',
				'value'       => ! empty( $approval_result ) ? $approval_result : self::APPROVED,
				'label'       => __( 'Beoordeling project', 'siw' ),
				'options'     => [
					self::APPROVED => __( 'Goedkeuren', 'siw' ),
					self::REJECTED => __( 'Afkeuren', 'siw' ),
				],
			]
		);
	}

	/** Toont beoordelingsresultaat */
	public function show_approval_result( \WP_Post $post ) {
		$product = siw_get_product( $post );
		if ( null == $product || empty( $product->get_meta( 'approval_result' ) ) ) {
			return;
		}
		switch ( $product->get_meta( 'approval_result' ) ) {
			case self::APPROVED:
				$message = __( 'Goedgekeurd', 'siw' );
				$class = 'success';
				break;
			case self::REJECTED;
				$message = __( 'Afgekeurd', 'siw' );
				$class = 'error';
				break;
		}

		printf (
			'<div class="notice notice-%s notice-alt inline">%s</div>',
			esc_attr( $class ),
			esc_html( $message )
		);
	}

	/** Slaat het resultaat van de beoordeling op */
	public function save_approval_result( \WC_Product $product ) {

		if ( ! isset( $_POST['_approval_result'] ) ) {
			return;
		}
		$product->update_meta_data( 'approval_result', wc_clean( $_POST['_approval_result'] ) );

		if ( self::REJECTED == wc_clean( $_POST['_approval_result'] ) ) {
			$product->set_catalog_visibility( 'hidden' );
		}
	}
}
