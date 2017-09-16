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
add_filter( 'load_textdomain_mofile', function( $mofile, $domain ) {
	$textdomains = array(
		'woocommerce',
		'pinnacle',
	);

	//$locale = is_admin() ? get_user_locale() : get_locale(); //TODO: nodig als site vertaald wordt

	if( in_array( $domain, $textdomains ) ) {
		$mofile = SIW_PLUGIN_DIR. "languages/{$domain}/nl_NL.mo";
	}

	return $mofile;
}, 10, 2 );
