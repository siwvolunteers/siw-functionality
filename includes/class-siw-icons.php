<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class voor SIW icons
 * 
 * @package   SIW
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_Icons {

	/**
	 * Init
	 */
	public static function init() {

		$self = new self();
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_style' ] );
		add_action( 'admin_enqueue_scripts', [ $self, 'enqueue_style' ] );		

		if ( class_exists( 'SiteOrigin_Widgets_Bundle' ) ) {
			add_filter( 'siteorigin_widgets_icon_families', [ $self, 'add_icon_family' ] );
			add_filter( 'siteorigin_widgets_icon_families', [ $self, 'remove_icon_families' ] );
		}
	}

	/**
	 * Voegt stylesheet toe
	 */
	public function enqueue_style(){
		wp_register_style( 'siw-icons', SIW_ASSETS_URL . 'css/siw-icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-icons' );
	}

	/**
	 * Voegt SIW-icon family toe
	 *
	 * @param array $icon_families
	 * @return array
	 */
	public function add_icon_family( $icon_families ) {
		$icon_families['siw'] = [
			'name'      => __( 'SIW Icons', 'siw' ),
			'style_uri' => SIW_ASSETS_URL . 'css/siw-admin-icons.css',
			'icons'     => self::get_icons( 'unicode', false ),
		];
		return $icon_families;
	}

	/**
	 * Verwijdert default icon families
	 *
	 * @param array $icon_families
	 * @return array
	 */
	public function remove_icon_families( $icon_families ) {
		unset( $icon_families['elegantline'] );
		unset( $icon_families['fontawesome'] );
		unset( $icon_families['genericons'] );
		unset( $icon_families['icomoon'] );
		unset( $icon_families['typicons'] );
		unset( $icon_families['ionicons'] );
		
		return $icon_families;
	}

	/**
	 * Geeft lijst van icons terug
	 *
	 * @param string $label html|unicode
	 * @param bool $prefix Moet prefix siw- toegevoegd worden?
	 * @return array
	 */
	public static function get_icons( $label = 'html', $prefix = true ) {
		$json_data = self::read_json_file();
		
		foreach ( $json_data as $icon => $unicode ) {
			if ( $prefix ) {
				$icon = 'siw-' . $icon;
			}
			switch ( $label ) {
				case 'unicode':
					$value = str_replace( "\\", "&#x", esc_attr( $unicode ) );
					break;
				case 'html':
					$value = "<i class='{$icon}'></i>";
					break;
			}
			$icons[ esc_attr( $icon ) ] = $value;
		}
		return $icons;
	}

	/**
	 * Haalt gegevens van icons op uit json file
	 * 
	 * @return array
	 * 
	 * @todo WP_Filesystem gebruiken?
	 */
	protected static function read_json_file() {
		$json_file = SIW_ASSETS_DIR . '/icons/siw-icons.json';
		if ( ! file_exists( $json_file ) ) {
			return [];
		}
		$json = file_get_contents( $json_file );
		$json_data = json_decode( $json, true );
		if ( ! is_array ( $json_data ) ) {
			return [];
		}
		return $json_data;
	}
}