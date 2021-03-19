<?php declare(strict_types=1);

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

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'wp_body_open', [ $self, 'add_svg_sprite']);

		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_script' ] );
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_style' ] );
		
		if ( class_exists( \SiteOrigin_Widgets_Bundle::class ) ) {
			add_action( 'siteorigin_panel_enqueue_admin_scripts', [ $self, 'enqueue_admin_style' ], PHP_INT_MAX );
			add_filter( 'siteorigin_widgets_icon_families', [ $self, 'add_icon_family' ] );
			add_filter( 'siteorigin_widgets_icon_families', [ $self, 'remove_icon_families' ] );
		}
	}

	/** Voegt SVG-sprite toe aan header */
	public function add_svg_sprite() {
		foreach ( array_keys( $this->get_icon_families() ) as $family ) {
			printf( '<div data-svg-url="%s" style="display:none;"></div>', SIW_ASSETS_URL . "icons/{$family}-icons.svg" );
		}
	}

	/** Voegt SVG-script toe */
	public function enqueue_script() {
		wp_enqueue_script( 'siw-svg' );
	}

	/** Voegt stylesheet toe */
	public function enqueue_style() {
		wp_register_style( 'siw-icons', SIW_ASSETS_URL . 'css/siw-icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( 'siw-icons' );
	}

	/** Voegt inline admin style voor icons toe */
	public function enqueue_admin_style() {
		
	foreach ( array_keys( $this->get_icon_families() ) as $family ) {
			$icons = $this->get_icons( $family );

			$rules[".siteorigin-widget-icon-selector-current .sow-icon-{$family}"] = [
				'max-width'  => '20px',
				'max-height' => '20px',
			];


			foreach ( $icons as $code ) {
				$rules[".sow-icon-{$family}[data-sow-icon='{$code}']"] = [
					'cursor' => 'url(' . SIW_ASSETS_URL . "icons/{$family}/{$code}.svg" . ')',
				];
			}
		}
		$inline_css = CSS::generate_inline_css( $rules );

		//TODO: onderstaande hack toelichten
		$inline_css = str_replace( 'cursor', 'content', $inline_css);

		wp_add_inline_style(
			'so-icon-field',
			$inline_css
		);
	}

	/** Geeft icon-families terug */
	protected function get_icon_families() : array {
		return [
			'siw' => __( 'SIW Icons', 'siw' ),
			'sdg' => __( 'Sustainable Development Goals', 'siw' ),	
		];
	}

	/** Voegt SIW-icon family toe */
	public function add_icon_family( array $icon_families ) : array {

		foreach ( $this->get_icon_families() as $family => $name ) {
			$icon_families[ $family ] = [
				'name'      => $name,
				'icons'     => $this->get_icons( $family ),
			];
		}
		return $icon_families;
	}

	/** Verwijdert default icon families */
	public function remove_icon_families( array $icon_families ) : array {
		unset( $icon_families['elegantline'] );
		unset( $icon_families['fontawesome'] );
		unset( $icon_families['genericons'] );
		unset( $icon_families['icomoon'] );
		unset( $icon_families['typicons'] );
		unset( $icon_families['ionicons'] );
		
		return $icon_families;
	}

	/** Geeft lijst van icons terug */
	protected function get_icons( string $family ) : array {

		$icons = wp_cache_get( $family, 'siw_icons' );
		if ( false !== $icons ) {
			return $icons;
		}

		//Icon-bestanden zoeken
		$icon_files = glob( SIW_ASSETS_DIR . "icons/{$family}/*.svg" );
		//Relatief pad van maken + extensie verwijderen
		array_walk(
			$icon_files,
			fn( string &$value ) : string => str_replace( [ SIW_ASSETS_DIR . "icons/{$family}/", '.svg'], '', $value )
		);

		foreach ( $icon_files as $icon_file ) {
			$icons[ "icon-{$icon_file}" ] = $icon_file;
		}
		wp_cache_set( $family, $icons, 'siw_icons' );
		return $icons;
	}
}
