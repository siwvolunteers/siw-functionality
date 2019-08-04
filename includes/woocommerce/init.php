<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Import-functies */
require_once( __DIR__ . '/import/data-descriptions.php' );

/* Export naar Plato TODO: class van maken */
require_once( __DIR__ . '/class-siw-wc-order-export.php' );
add_action( 'plugins_loaded', ['SIW_WC_Order_Export', 'init']);

/* Frontend*/
require_once( __DIR__ . '/class-siw-wc-product-archive.php' );
require_once( __DIR__ . '/class-siw-wc-product.php' );

add_action( 'plugins_loaded', ['SIW_WC_Product_Archive', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Product', 'init']);

/* Checkout */
require_once( __DIR__ . '/data-address-fields.php' );
require_once( __DIR__ . '/data-checkout-fields.php' );


require_once( __DIR__ . '/class-siw-wc-coupon.php' );
add_action( 'plugins_loaded', ['SIW_WC_Coupon', 'init']);
