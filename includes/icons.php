<?php declare(strict_types=1);

namespace SIW;

use SIW\Assets\SIW_SVG;
use SIW\Util\CSS;

/**
 * Class voor SIW icons
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Icons {

	const ASSETS_HANDLE = 'siw-icons';

	/** Init */
	public static function init() {
		$self = new self();

		add_action( 'wp_body_open', [ $self, 'add_svg_sprite' ] );

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
		printf( '<div data-svg-url="%s" style="display:none;"></div>', esc_url( SIW_ASSETS_URL . 'icons/siw-general-icons.svg' ) );
	}

	/** Voegt SVG-script toe */
	public function enqueue_script() {
		wp_enqueue_script( SIW_SVG::ASSETS_HANDLE );
	}

	/** Voegt stylesheet toe */
	public function enqueue_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/siw-icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	/** Voegt inline admin style voor icons toe */
	public function enqueue_admin_style() {
		$icons = $this->get_icons();

		$rules['.siteorigin-widget-icon-selector-current .sow-icon-siw'] = [
			'max-width'  => '20px',
			'max-height' => '20px',
		];
		foreach ( $icons as $icon => $code ) {
			$rules[ ".sow-icon-siw[data-sow-icon='{$code}']" ] = [
				'cursor' => 'url(' . SIW_ASSETS_URL . "icons/general/{$code}.svg" . ')',
			];
		}

		$inline_css = CSS::generate_inline_css( $rules );

		// TODO: onderstaande hack toelichten
		$inline_css = str_replace( 'cursor', 'content', $inline_css );

		wp_add_inline_style(
			'so-icon-field',
			$inline_css
		);
	}

	/** Voegt SIW-icon family toe */
	public function add_icon_family( array $icon_families ): array {
		$icon_families['siw'] = [
			'name'  => __( 'SIW Icons', 'siw' ),
			'icons' => $this->get_icons(),
		];
		return $icon_families;
	}

	/** Verwijdert default icon families */
	public function remove_icon_families( array $icon_families ): array {
		unset( $icon_families['elegantline'] );
		unset( $icon_families['fontawesome'] );
		unset( $icon_families['genericons'] );
		unset( $icon_families['icomoon'] );
		unset( $icon_families['typicons'] );
		unset( $icon_families['ionicons'] );

		return $icon_families;
	}

	/** Geeft lijst van icons terug */
	protected function get_icons(): array {

		$icons = wp_cache_get( 'icons', 'siw_icons' );
		if ( false !== $icons ) {
			return $icons;
		}

		// Icon-bestanden zoeken
		$icon_files = glob( SIW_ASSETS_DIR . 'icons/general/*.svg' );
		// Relatief pad van maken + extensie verwijderen
		array_walk(
			$icon_files,
			function( string &$value ) {
				$value = str_replace( [ SIW_ASSETS_DIR . 'icons/general/', '.svg' ], '', $value );
			}
		);

		foreach ( $icon_files as $icon_file ) {
			$icons[ "icon-{$icon_file}" ] = $icon_file;
		}
		wp_cache_set( 'icons', $icons, 'siw_icons' );
		return $icons;
	}
}
