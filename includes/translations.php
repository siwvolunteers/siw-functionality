<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Laad custom vertalingen voor
 * - WooCommerce
 * - Pinnacle Premium
 */
add_action( 'plugins_loaded', function() {
	$textdomains = array(
		'woocommerce',
		'pinnacle',
	);
	foreach ( $textdomains as $textdomain ) {
		unload_textdomain( $textdomain );
		load_textdomain( $textdomain, SIW_PLUGIN_DIR. "languages/{$textdomain}/nl_NL.mo" );
	}
} );
