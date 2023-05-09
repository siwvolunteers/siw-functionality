<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Assets\SIW_SVG;
use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Util\CSS;

/**
 * Class voor SIW icons
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Icons extends Base {

	const ASSETS_HANDLE = 'siw-icons';

	#[Action( 'wp_body_open' )]
	/** Voegt SVG-sprite toe aan header */
	public function add_svg_sprite() {
		printf( '<div data-svg-url="%s" style="display:none;"></div>', esc_url( SIW_ASSETS_URL . 'icons/siw-general-icons.svg' ) );
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt SVG-script toe */
	public function enqueue_script() {
		wp_enqueue_script( SIW_SVG::ASSETS_HANDLE );
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_style() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/features/icons.css', null, SIW_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	#[Action( 'siteorigin_panel_enqueue_admin_scripts', PHP_INT_MAX )]
	/** Voegt inline admin style voor icons toe */
	public function enqueue_admin_style() {
		$icons = $this->get_icons();

		$css = CSS::get_css_generator();
		$css->add_rule(
			'.siteorigin-widget-icon-selector-current .sow-icon-siw',
			[
				'max-width'  => '20px',
				'max-height' => '20px',
			]
		);

		foreach ( $icons as $icon => $code ) {
			$css->add_rule(
				".sow-icon-siw[data-sow-icon='{$code}']",
				[
					'content' => 'url(' . SIW_ASSETS_URL . "icons/general/{$code}.svg" . ')',
				]
			);
		}

		wp_add_inline_style(
			'so-icon-field',
			$css->get_output()
		);
	}

	#[Filter( 'siteorigin_widgets_icon_families' )]
	/** Voegt SIW-icon family toe */
	public function add_icon_family( array $icon_families ): array {
		$icon_families['siw'] = [
			'name'  => __( 'SIW Icons', 'siw' ),
			'icons' => $this->get_icons(),
		];
		return $icon_families;
	}

	#[Filter( 'siteorigin_widgets_icon_families' )]
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

		$icons = [];
		foreach ( $icon_files as $icon_file ) {
			$icons[ "icon-{$icon_file}" ] = $icon_file;
		}
		wp_cache_set( 'icons', $icons, 'siw_icons' );
		return $icons;
	}
}
