<?php declare(strict_types=1);

namespace SIW\WooCommerce\Product\Admin;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\Compatibility\WooCommerce;
use SIW\Facades\WooCommerce as WooCommerce_Facade;
use SIW\WooCommerce\Import\Product as Import_Product;
use SIW\WooCommerce\Product\WC_Product_Project;

class Approval extends Base {

	private const FIELD_ID = '_approval_result';
	public const REJECTED = 'rejected';
	public const APPROVED = 'approved';

	#[Add_Action( 'post_submitbox_misc_actions' )]
	public function show_approval_option( \WP_Post $post ) {
		if ( WooCommerce::PRODUCT_POST_TYPE !== $post->post_type || Import_Product::REVIEW_STATUS !== $post->post_status ) {
			return;
		}
		$product = WooCommerce_Facade::get_product( $post->ID );

		if ( null === $product ) {
			return;
		}

		$approval_result = $product->get_approval_result();
		woocommerce_wp_radio(
			[
				'id'      => self::FIELD_ID,
				'value'   => ! empty( $approval_result ) ? $approval_result : self::APPROVED,
				'label'   => __( 'Beoordeling project', 'siw' ),
				'options' => [
					self::APPROVED => __( 'Goedkeuren', 'siw' ),
					self::REJECTED => __( 'Afkeuren', 'siw' ),
				],
			]
		);
	}

	#[Add_Action( 'post_submitbox_start' )]
	public function show_approval_result( \WP_Post $post ) {
		$product = WooCommerce_Facade::get_product( $post );
		if ( null === $product || empty( $product->get_approval_result() ) ) {
			return;
		}
		switch ( $product->get_approval_result() ) {
			case self::APPROVED:
				$message = __( 'Goedgekeurd', 'siw' );
				$class = 'success';
				break;
			case self::REJECTED:
				$message = __( 'Afgekeurd', 'siw' );
				$class = 'error';
				break;
			default:
				return;
		}

		printf(
			'<div class="notice notice-%s notice-alt inline">%s</div>',
			esc_attr( $class ),
			esc_html( $message )
		);
	}

	#[Add_Action( 'woocommerce_admin_process_product_object' )]
	public function save_approval_result( WC_Product_Project $product ) {

		// phpcs:disable WordPress.Security.NonceVerification.Missing

		if ( ! isset( $_POST[ self::FIELD_ID ] ) ) {
			return;
		}

		$approval_result = sanitize_text_field( wp_unslash( $_POST[ self::FIELD_ID ] ) );

		$product->set_approval_result( $approval_result );

		if ( self::REJECTED === $approval_result ) {
			$product->set_catalog_visibility( 'hidden' );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}
}
