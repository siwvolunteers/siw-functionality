<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Admin\Notices as Admin_Notices;
use SIW\WooCommerce\Import\Product as Import_Product;
use SIW\WooCommerce\Admin\Product_Tabs;

/**
 * Aanpassingen aan admin-scherm voor producten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
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
		//Beoordelen projecten
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
			$product = \siw_get_product( absint( $_GET['product_id'] ) );

			if ( null != $product ) {
				$product->set_selected_for_carousel( ! $product->is_selected_for_carousel() );
				$product->save();
			}
		}

		wp_safe_redirect( wp_get_referer() ? remove_query_arg( ['trashed', 'untrashed', 'deleted', 'ids'], wp_get_referer() ) : admin_url( 'edit.php?post_type=product' ) );
		exit;
	}
}
