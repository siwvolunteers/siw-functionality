<?php

namespace SIW;

/**
 * Functionaliteit t.b.v. meertaligheid
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class i18n {

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

	/**
	 * Zoekt url van vertaalde pagina op basis van id
	 * @param  int $page_id
	 * @return string
	 */
	public static function get_translated_page_url( int $page_id ) {
		$translated_page_id = self::get_translated_page_id( $page_id );
		return get_page_link( $translated_page_id );
	}

	/**
	 * Zoekt id van vertaalde pagina op basis van id
	 * @param  int $page_id
	 * @return int
	 */
	public static function get_translated_page_id( int $page_id ) {
		return apply_filters( 'wpml_object_id', $page_id, 'page', true );
	}

	/**
	 * Geeft vertaalde permalink in meegegeven taal terug
	 *
	 * @param string $permalink
	 * @param string $language_code
	 * @return string
	 */
	public static function get_translated_permalink( string $permalink, string $language_code ) {
		return apply_filters( 'wpml_permalink', $permalink, $language_code );
	}

	/**
	 * Geeft terug of de huidige taal gelijk is aan de standaardtaal
	 *
	 * @return bool
	 */
	public static function is_default_language() {
		return ( apply_filters( 'wpml_current_language', NULL ) == apply_filters( 'wpml_default_language', NULL ) ); 
	}

	/**
	 * Geeft code van huidige taal terug
	 * 
	 * @return string
	 */
	public static function get_current_language() {
		return apply_filters( 'wpml_current_language', NULL );
	}

	/**
	 * Geeft code van standaardtaal terug
	 * 
	 * @return string
	 */
	public static function get_default_language() {
		return apply_filters( 'wpml_default_language', NULL );
	}

	/**
	 * Geeft gegevens van actieve talen terug
	 * 
	 * @return array
	 */
	public static function get_active_languages() {
		return apply_filters( 'wpml_active_languages', null );
	}
}

