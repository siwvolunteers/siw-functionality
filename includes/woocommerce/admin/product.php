<?php declare(strict_types=1);

namespace SIW\WooCommerce\Admin;

use SIW\Attributes\Add_Action;
use SIW\Base;

class Product extends Base {

	#[Add_Action( 'admin_init', 20 )]
	public function manage_admin_columns() {
		if ( ! class_exists( '\MBAC\Post' ) ) {
			return;
		}
		new Product_Columns( 'product', [] );
	}

	#[Add_Action( 'add_meta_boxes', PHP_INT_MAX )]
	public function remove_meta_boxes() {
		remove_meta_box( 'slugdiv', 'product', 'normal' );
		remove_meta_box( 'postexcerpt', 'product', 'normal' );
		if ( ! current_user_can( 'manage_options' ) ) {
			remove_meta_box( 'woocommerce-product-images', 'product', 'side' );
			remove_meta_box( 'product_catdiv', 'product', 'normal' );
		}
	}
}
