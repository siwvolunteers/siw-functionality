<?php

namespace SIW\WooCommerce\Admin;

use SIW\Admin\Notices as Admin_Notices;
use SIW\WooCommerce\Import\Product as Import_Product;
use SIW\WooCommerce\Admin\Product_Tabs;

/**
 * Aanpassingen aan admin-scherm voor producten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Product {

	public static function init() {
		$self = new self();

		add_action( 'add_meta_boxes', [ $self, 'remove_meta_boxes'], PHP_INT_MAX );
		add_filter( 'woocommerce_duplicate_product_capability', '__return_empty_string' );

		add_action( 'init', [ $self, 'remove_editor'], PHP_INT_MAX ); 

		add_filter( 'manage_edit-product_columns', [ $self, 'remove_admin_columns'] );
		add_action( 'admin_init', [ $self, 'add_admin_columns'], 20 );
		add_filter( 'bulk_actions-edit-product', [ $self, 'add_bulk_actions'] );
		add_filter( 'handle_bulk_actions-edit-product', [ $self, 'handle_bulk_actions'], 10, 3 );
		
		//Beoordelen projecten
		add_action( 'post_submitbox_start', [ $self, 'show_approval_option'] );
		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_approval_result'] );

		Product_Tabs::init();
	}

	/**
	 * Verwijdert de texteditor
	 */
	public function remove_editor() {
		remove_post_type_support( 'product', 'editor' );
	}

	/**
	 * Verwijdert overbodige admin columns
	 *
	 * @param array $columns
	 * @return array
	 */
	public function remove_admin_columns( array $columns ) : array {
		unset( $columns['thumb']);
		unset( $columns['date'] );
		unset( $columns['product_tag'] );
		unset( $columns['price'] );
		return $columns;
	}

	/**
	 * Voegt extra admin columns toe
	 */
	public function add_admin_columns() {
		if ( ! class_exists( '\MBAC\Post' ) ) {
			return;
		}
		new Product_Columns( 'product', [] );
	}

	/**
	 * Voegt bulk acties toe
	 * 
	 * - Opnieuw importeren
	 *
	 * @param array $bulk_actions
	 * @return array
	 */
	public function add_bulk_actions( array $bulk_actions ) : array {
		$bulk_actions['import_again'] = __( 'Opnieuw importeren', 'siw' );
		return $bulk_actions;
	}

	/**
	 * Verwerkt bulkacties
	 *
	 * @param string $redirect_to
	 * @param string $action
	 * @param array $post_ids
	 * @return string
	 */
	public function handle_bulk_actions( string $redirect_to, string $action, $post_ids ) : string {
		if ( 'import_again' === $action ) {
			foreach ( $post_ids as $post_id ) {
				update_post_meta( $post_id, 'import_again', true );
			}
			$notices = new Admin_Notices;
			$count = count( $post_ids );
			$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
			$notices->add_notice( 'info', $message , true);
		}
		return $redirect_to;
	}

	/**
	 * Toont optie om nog niet gepubliceerd project af of goed te keuren
	 *
	 * @param \WP_Post $post
	 */
	public function show_approval_option( $post ) {
		if ( 'product' != $post->post_type || Import_Product::REVIEW_STATUS != $post->post_status ) {
			return;
		}
		$product = wc_get_product( $post->ID );
		$approval_result = $product->get_meta( 'approval_result' );
		woocommerce_wp_radio(
			[
				'id'          => 'approval_result',
				'value'       => ! empty( $approval_result ) ? $approval_result : 'approved',
				'label'       => __( 'Beoordeling project', 'siw' ),
				'options'     => [
					'approved' => __( 'Goedkeuren', 'siw' ),
					'rejected' => __( 'Afkeuren', 'siw' ),
				],
			]
		);
	}
	
	/**
	 * Slaat het resultaat van de beoordeling op
	 *
	 * @param \WC_Product $product
	 */
	public function save_approval_result( \WC_Product $product ) {

		if ( ! isset( $_POST['approval_result'] ) ) {
			return;
		}

		$meta_data = [
			'approval_result' => wc_clean( $_POST['approval_result'] ),
			'approval_user'   => wp_get_current_user()->display_name,
			'approval_date'   => current_time( 'Y-m-d' ),
		];

		foreach ( $meta_data as $key => $value ) {
			$product->update_meta_data( $key, $value );
		}

		if ( 'rejected' == $meta_data['approval_result'] ) {
			$product->set_catalog_visibility( 'hidden' );
		}
	}

	/**
	 * Verwijdert metaboxes
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'slugdiv', 'product', 'normal' );
		remove_meta_box( 'postcustom' , 'product' , 'normal' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_meta_box( 'woocommerce-product-images' , 'product', 'side' );
			remove_meta_box( 'tagsdiv-product_tag', 'product', 'normal' );
			remove_meta_box( 'product_catdiv', 'product', 'normal' );
		}
	}
}
