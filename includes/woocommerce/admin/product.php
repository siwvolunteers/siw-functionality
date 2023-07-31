<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;

/**
 * Aanpassingen aan admin-scherm voor producten
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Product extends Base {

	#[Filter( 'woocommerce_duplicate_product_capability' )]
	private const DUPLICATE_PRODUCT_CAPABILITY = '';

	#[Action( 'admin_menu', PHP_INT_MAX )]
	public function remove_product_tags_admin_menu() {
		remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product' );
	}

	#[Action( 'init', PHP_INT_MAX )]
	public function remove_editor() {
		remove_post_type_support( 'product', 'editor' );
	}

	#[Action( 'admin_init', 20 )]
	public function manage_admin_columns() {
		if ( ! class_exists( '\MBAC\Post' ) ) {
			return;
		}
		new Product_Columns( 'product', [] );
	}

	#[Action( 'add_meta_boxes', PHP_INT_MAX )]
	public function remove_meta_boxes() {
		remove_meta_box( 'slugdiv', 'product', 'normal' );
		remove_meta_box( 'postcustom', 'product', 'normal' );
		remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
		remove_meta_box( 'postexcerpt', 'product', 'normal' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_meta_box( 'woocommerce-product-images', 'product', 'side' );
			remove_meta_box( 'product_catdiv', 'product', 'normal' );
		}
	}

	#[Filter( 'quick_edit_show_taxonomy' )]
	public function hide_product_tags_quick_edit( bool $show, string $taxonomy_name, string $post_type ): bool {
		if ( 'product_tag' === $taxonomy_name ) {
			$show = false;
		}
		return $show;
	}
}
