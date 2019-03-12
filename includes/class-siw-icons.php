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
			'icons'     => $this->get_icons_from_json(),
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
	 * Haalt gegevens van icons op uit json file
	 * 
	 * @return array
	 * 
	 * @todo WP_Filesystem gebruiken?
	 */
	protected function get_icons_from_json() {

		$json_file = SIW_ASSETS_DIR . '/icons/siw-icons.json';
		if ( ! file_exists( $json_file ) ) {
			return [];
		}
		$json = file_get_contents( $json_file );
		$json_data = json_decode( $json, true );
		if ( ! is_array ( $json_data ) ) {
			return [];
		}
		foreach ( $json_data as $icon => $unicode ) {
			$icons[ esc_attr( $icon ) ] = str_replace( "\\", "&#x", esc_attr( $unicode ) );
		}

		return $icons;
	}
}