<?php declare(strict_types=1);

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
		add_action( 'admin_menu', [ $self, 'remove_product_tags_admin_menu'], PHP_INT_MAX );
		add_filter( 'quick_edit_show_taxonomy', [ $self, 'hide_product_tags_quick_edit' ], 10, 3 );
		add_filter( 'bulk_actions-edit-product', [ $self, 'add_bulk_actions'] );
		add_filter( 'handle_bulk_actions-edit-product', [ $self, 'handle_bulk_actions'], 10, 3 );
		
		//Beoordelen projecten
		add_action( 'post_submitbox_start', [ $self, 'show_approval_option'] );
		add_action( 'woocommerce_admin_process_product_object', [ $self, 'save_approval_result'] );

		Product_Tabs::init();

		add_action( 'wp_ajax_woocommerce_select_for_carousel', [ $self, 'select_for_carousel' ] );
	}
	
	/** Verwijdert admin menu voor tags */
	public function remove_product_tags_admin_menu() {
		remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product' );
	}

	/** Verwijdert de texteditor */
	public function remove_editor() {
		remove_post_type_support( 'product', 'editor' );
	}

	/** Verwijdert overbodige admin columns */
	public function remove_admin_columns( array $columns ) : array {
		unset( $columns['thumb']);
		unset( $columns['date'] );
		unset( $columns['product_tag'] );
		unset( $columns['price'] );
		return $columns;
	}

	/** Voegt extra admin columns toe */
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
	 * - Selecteren voor carousel
	 */
	public function add_bulk_actions( array $bulk_actions ) : array {
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
				$products = wc_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$product->update_meta_data( 'import_again', true );
						$product->save();
					}
				);
				$message = sprintf( _n( '%s project wordt opnieuw geïmporteerd.', '%s projecten worden opnieuw geïmporteerd.', $count, 'siw' ), $count );
				break;
			case 'select_for_carousel':
				$products = wc_get_products( ['include' => $post_ids ] );
				array_walk(
					$products,
					function( \WC_Product $product ) {
						$product->update_meta_data( 'selected_for_carousel', true );
						$product->save();
					}
				);
				$message = sprintf( _n( '%s project is geselecteerd voor de carousel.', '%s projecten zijn geselecteerd voor de carousel.', $count, 'siw' ), $count );
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
			default:
		}

		if ( isset( $message ) ) {
			$notices = new Admin_Notices;
			$notices->add_notice( 'info', $message , true);
		}

		return $redirect_to;
	}

	/** Toont optie om nog niet gepubliceerd project af of goed te keuren */
	public function show_approval_option( \WP_Post $post ) {
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
	
	/** Slaat het resultaat van de beoordeling op */
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

	/** Verwijdert metaboxes */
	public function remove_meta_boxes() {
		remove_meta_box( 'slugdiv', 'product', 'normal' );
		remove_meta_box( 'postcustom' , 'product' , 'normal' );
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_meta_box( 'woocommerce-product-images' , 'product', 'side' );
			remove_meta_box( 'product_catdiv', 'product', 'normal' );
		}
	}

	/** Verberg tags in quick edit */
	public function hide_product_tags_quick_edit( bool $show, string $taxonomy_name, string $post_type ) : bool {
		if ( 'product_tag' == $taxonomy_name ) {
			$show = false;
		}
		return $show;
	}

	/** Verwerk selecteren voor carousel */
	public function select_for_carousel() {
		if ( current_user_can( 'edit_products' ) && check_admin_referer( 'woocommerce-select-for-carousel' ) && isset( $_GET['product_id'] ) ) {
			$product = wc_get_product( absint( $_GET['product_id'] ) );

			if ( $product ) {
				$product->update_meta_data( 'selected_for_carousel', ! $product->get_meta( 'selected_for_carousel') );
				$product->save();
			}
		}

		wp_safe_redirect( wp_get_referer() ? remove_query_arg( ['trashed', 'untrashed', 'deleted', 'ids'], wp_get_referer() ) : admin_url( 'edit.php?post_type=product' ) );
		exit;
	}
}
