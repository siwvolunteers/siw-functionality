<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Functionaliteit t.b.v. meertaligheid
 * 
 * @package   SIW
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */

class SIW_i18n {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'load_textdomain_mofile', [ $self, 'load_custom_translations'], 10, 2 );
		load_plugin_textdomain( 'siw', false, SIW_PLUGIN_DIR . 'languages/siw/' );
		add_filter( 'wpml_ls_directories_to_scan', [ $self, 'add_language_switcher_templates_dir'] );
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
	public function load_custom_translations( $mofile, $domain ) {
		$textdomains['nl_NL'] = [ 'woocommerce', 'pinnacle' ];
		$textdomains['en_US'] = [ 'siw' ];
	
		$locale = is_admin() ? get_user_locale() : get_locale();
	
		if ( isset( $textdomains[ $locale ] ) && in_array( $domain, $textdomains[ $locale ] ) ) {
			$custom_mofile = SIW_PLUGIN_DIR . "languages/{$domain}/{$locale}.mo";
			$mofile = file_exists( $custom_mofile ) ? $custom_mofile : $mofile;
		}
	
		return $mofile;
	}

	/**
	 * Zoekt url van vertaalde pagina op basis van id
	 * @param  int $page_id
	 * @return string
	 */
	public static function get_translated_page_url( $page_id ) {
		$translated_page_id = self::get_translated_page_id( $page_id );
		$translated_page_url = get_page_link( $translated_page_id );
		return $translated_page_url;
	}

	/**
	 * Zoekt id van vertaalde pagina op basis van id
	 * @param  int $page_id
	 * @return int
	 */
	public static function get_translated_page_id( $page_id ) {
		$translated_page_id = apply_filters( 'wpml_object_id', $page_id, 'page', true );
		return $translated_page_id;
	}

	/**
	 * Geeft terug of de huidige taal gelijk is aan de standaardtaal
	 *
	 * @return boolean
	 */
	public static function is_default_language() {
		return ( apply_filters( 'wpml_current_language', NULL ) == apply_filters( 'wpml_default_language', NULL ) ); 
	}

	/**
	 * Voegt templates voor taalwisselaar toe
	 *
	 * @param array $dirs
	 * @return array
	 */
	public function add_language_switcher_templates_dir( $dirs ) {
		$dirs[] = SIW_TEMPLATES_DIR .'/wpml/language-switchers';
		return $dirs;
	}
}
