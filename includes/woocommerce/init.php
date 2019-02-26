<?php
/*
 * (c)2017-2019 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* E-mail-functies */
require_once( __DIR__ . '/email/class-siw-wc-emails.php');
require_once( __DIR__ . '/email/class-siw-wc-email-new-order.php');
require_once( __DIR__ . '/email/class-siw-wc-email-customer-on-hold-order.php');
require_once( __DIR__ . '/email/class-siw-wc-email-customer-processing-order.php');

add_action( 'plugins_loaded', ['SIW_WC_Emails', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Email_New_Order', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Email_Customer_On_Hold_Order', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Email_Customer_Processing_Order', 'init']);

/* Import-functies */
require_once( __DIR__ . '/import/class-siw-wc-import-product.php');
require_once( __DIR__ . '/import/data-descriptions.php' );


/* Export naar Plato TODO: class van maken */
require_once( __DIR__ . '/export-to-plato.php' );

/** Admin */
require_once( __DIR__ . '/admin/class-siw-wc-admin-order.php' );
require_once( __DIR__ . '/admin/class-siw-wc-admin-product.php' );

add_action( 'plugins_loaded', ['SIW_WC_Admin_Order', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Admin_Product', 'init']);

/* Frontend*/
require_once( __DIR__ . '/class-siw-wc-product-archive.php' );
require_once( __DIR__ . '/class-siw-wc-product.php' );

add_action( 'plugins_loaded', ['SIW_WC_Product_Archive', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Product', 'init']);

/* Checkout */
require_once( __DIR__ . '/data-address-fields.php' );
require_once( __DIR__ . '/data-checkout-fields.php' );
require_once( __DIR__ . '/class-siw-wc-checkout.php' );
require_once( __DIR__ . '/class-siw-wc-checkout-newsletter.php' );

add_action( 'plugins_loaded', ['SIW_WC_Checkout', 'init']);
add_action( 'plugins_loaded', ['SIW_WC_Checkout_Newsletter', 'init']);
