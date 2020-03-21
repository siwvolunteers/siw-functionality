<?php

namespace SIW;

/**
 * Vertalingen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.4
 */
class Translations {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'load_textdomain_mofile', [ $self, 'load_custom_translations'], 10, 2 );
		load_plugin_textdomain( 'siw', false, SIW_PLUGIN_DIR . 'languages/siw/' );
	}

	/**
	 * Laad custom vertalingen voor
	 * 
	 * - WooCommerce
	 * - Pinnacle Premium
	 * - SIW plugin en thema
	 * @param string $mofile
	 * @param string $domain
	 * @return string
	 */
	public function load_custom_translations( string $mofile, string $domain ) {
		$textdomains['nl_NL'] = [ 'woocommerce', 'pinnacle' ];
		$textdomains['en_US'] = [ 'siw' ];
	
		$locale = is_admin() ? get_user_locale() : get_locale();
	
		if ( isset( $textdomains[ $locale ] ) && in_array( $domain, $textdomains[ $locale ] ) ) {
			$custom_mofile = SIW_PLUGIN_DIR . "languages/{$domain}/{$locale}.mo";
			$mofile = file_exists( $custom_mofile ) ? $custom_mofile : $mofile;
		}
		return $mofile;
	}
}
