<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH') ) {
	exit;
}

/* Backend-functies */
require_once( __DIR__ . '/admin/product.php' );
require_once( __DIR__ . '/admin/order.php' );

/* Frontend-functies */
require_once( __DIR__ . '/frontend/archive.php' );
require_once( __DIR__ . '/frontend/product.php' );

/* Checkout-functies */
require_once( __DIR__ . '/checkout/newsletter.php' );
require_once( __DIR__ . '/checkout/save.php' );
require_once( __DIR__ . '/checkout/customer.php' );
require_once( __DIR__ . '/checkout/validate.php' );
require_once( __DIR__ . '/checkout/form.php' );

/* E-mail-functies */
require_once( __DIR__ . '/email/application.php' );
require_once( __DIR__ . '/email/approval.php' );

/* Import-functies */
require_once( __DIR__ . '/import/attributes.php' );
require_once( __DIR__ . '/import/image.php' );
require_once( __DIR__ . '/import/process.php' );
require_once( __DIR__ . '/import/description.php' );
require_once( __DIR__ . '/import/wp-all-import.php' );

/* Diverse functies */
require_once( __DIR__ . '/cleanup.php' );
require_once( __DIR__ . '/update.php' );
require_once( __DIR__ . '/export-to-plato.php' );
