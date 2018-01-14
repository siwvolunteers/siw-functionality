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
 * - SIW plugin en thema
 */
add_filter( 'load_textdomain_mofile', function( $mofile, $domain ) {

	$textdomains = array();

	$textdomains['nl_NL'] = array(
		'woocommerce',
		'pinnacle',
	);
	$textdomains['en_US'] = array(
		'siw',
	);

	$locale = is_admin() ? get_user_locale() : get_locale();

	if ( isset( $textdomains[ $locale ] ) && in_array( $domain, $textdomains[ $locale ] ) ) {
		$custom_mofile = SIW_PLUGIN_DIR . "languages/{$domain}/{$locale}.mo";
		$mofile = file_exists( $custom_mofile ) ? $custom_mofile : $mofile;
	}

	return $mofile;
}, 10, 2 );

add_action( 'plugins_loaded', function() {
	load_plugin_textdomain( 'siw', false, SIW_PLUGIN_DIR . 'languages/siw/' );
});
