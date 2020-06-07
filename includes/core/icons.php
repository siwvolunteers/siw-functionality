<?php

namespace SIW\Core;

use SIW\HTML;
use SIW\Util\CSS;

/**
 * Class voor SIW icons
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Icons {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();

		add_action( 'wp_body_open', [ $self, 'add_svg_sprite']);

		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_script' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_style' ] );
		
		if ( class_exists( 'SiteOrigin_Widgets_Bundle' ) ) {
			add_action( 'siteorigin_panel_enqueue_admin_scripts', [ $self, 'enqueue_admin_style' ], PHP_INT_MAX );
			add_filter( 'siteorigin_widgets_icon_families', [ $self, 'add_icon_family' ] );
			add_filter( 'siteorigin_widgets_icon_families', [ $self, 'remove_icon_families' ] );
		}
	}

	/**
	 * Voegt SVG-sprite toe aan header
	 */
	public function add_svg_sprite() {
		echo HTML::div(
			[
				'data-svg-url' => SIW_ASSETS_URL . 'siw-icons.svg',
				'style'        => 'display:none;',
			]
		);
	}

	/**
	 * Voegt SVG-script toe
	 */
	public function enqueue_script() {
		wp_enqueue_script( 'siw-svg' );
	}

	/**
	 * Voegt stylesheet toe
	 */
	public function enqueue_style() {
		wp_register_style( 'siw-icons', SIW_ASSETS_URL . 'css/siw-icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-icons' );
	}

	/**
	 * Voegt inline admin style voor icons toe
	 */
	public function enqueue_admin_style() {
		$icons = $this->get_icons();

		$rules['.siteorigin-widget-icon-selector-current .sow-icon-siw'] = [
			'max-width'  => '20px',
			'max-height' => '20px',
		];
		foreach ( $icons as $icon => $code ) {
			$rules[".sow-icon-siw[data-sow-icon='{$code}']"] = [
				'cursor' => 'url(' . SIW_ASSETS_URL . "icons/{$code}.svg" . ')',
			];
		}

		$inline_css = CSS::generate_inline_css( $rules );

		//TODO: onderstaande hack toelichten
		$inline_css = str_replace( 'cursor', 'content', $inline_css);

		wp_add_inline_style(
			'so-icon-field',
			$inline_css
		);
	}

	/**
	 * Voegt SIW-icon family toe
	 *
	 * @param array $icon_families
	 * @return array
	 */
	public function add_icon_family( array $icon_families ) : array {
		$icon_families['siw'] = [
			'name'      => __( 'SIW Icons', 'siw' ),
			'icons'     => $this->get_icons(),
		];
		return $icon_families;
	}

	/**
	 * Verwijdert default icon families
	 *
	 * @param array $icon_families
	 * @return array
	 */
	public function remove_icon_families( array $icon_families ) : array {
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
	 * @return array
	 */
	protected function get_icons() : array {

		$icons = wp_cache_get( 'icons', 'siw_icons' );
		if ( false !== $icons ) {
			return $icons;
		}

		//Icon-bestanden zoeken
		$icon_files = glob( SIW_ASSETS_DIR . '/icons/*.svg' );
		//Relatief pad van maken + extensie verwijderen
		array_walk( $icon_files, function(&$value, &$key) {
			$value = str_replace( [ SIW_ASSETS_DIR .'/icons/', '.svg'], '', $value );
		});

		foreach ( $icon_files as $icon_file ) {
			$icons[ "icon-{$icon_file}" ] = $icon_file;
		}
		wp_cache_set( 'icons', $icons, 'siw_icons' );
		return $icons;
	}
}
