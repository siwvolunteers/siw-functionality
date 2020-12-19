<?php declare(strict_types=1);

namespace SIW\Core;

/**
 * Vertalingen
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.4
 */
class Translations {

	/**
	 * Custom translations
	 */
	protected array $custom_translations = [
		'nl_NL' => [ 'woocommerce' ]
	];

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_filter( 'load_textdomain_mofile', [ $self, 'load_custom_translations'], 10, 2 );

		$translations = [
			'siw' => 'siw-functionality/languages/',
		];

		//Filter t.b.v. extensies
		$translations = apply_filters( 'siw_translations', $translations );

		foreach ( $translations as $textdomain => $directory ) {
			load_plugin_textdomain( $textdomain, false, $directory );
		}
	}

	/**
	 * Laad custom vertalingen voor
	 * 
	 * - WooCommerce
	 * 
	 * @param string $mofile
	 * @param string $domain
	 * @return string
	 */
	public function load_custom_translations( string $mofile, string $domain ) : string {
		$locale = determine_locale();
	
		if ( isset( $this->custom_translations[ $locale ] ) && in_array( $domain, $this->custom_translations[ $locale ] ) ) {
			$custom_mofile = SIW_PLUGIN_DIR . "languages/{$domain}/{$locale}.mo";
			$mofile = file_exists( $custom_mofile ) ? $custom_mofile : $mofile;
		}
		return $mofile;
	}
}
