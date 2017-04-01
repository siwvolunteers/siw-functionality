<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH') ) {
	exit;
}

/* Backend-functies */
require_once( 'admin/product.php' );
require_once( 'admin/order.php' );

/* Frontend-functies */
require_once( 'frontend/archive.php' );
require_once( 'frontend/product.php' );

/* Checkout-functies */
require_once( 'checkout/newsletter.php' );
require_once( 'checkout/save.php' );
require_once( 'checkout/customer.php' );
require_once( 'checkout/validate.php' );
require_once( 'checkout/form.php' );

/* E-mail-functies */
require_once( 'email/application.php' );
require_once( 'email/approval.php' );

/* Import-functies */
require_once( 'import/attributes.php' );
require_once( 'import/image.php' );
require_once( 'import/process.php' );
require_once( 'import/description.php' );
require_once( 'import/wp-all-import.php' );

/* Diverse functies */
require_once( 'cleanup.php' );
require_once( 'update.php' );
require_once( 'export-to-plato.php' );
