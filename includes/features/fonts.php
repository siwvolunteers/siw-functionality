<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;

/**
 * Lettertype
 *
 * @copyright 2024 SIW Internationale Vrijwilligersprojecten
 * @see https://gwfh.mranftl.com/fonts/
 */
class Fonts extends Base {

	#[Add_Action( 'wp_head' )]
	public function add_font_faces(): void {
		$fonts = [];
		foreach ( $this->get_font_families() as $font_family => $properties ) {
			$fonts[ $font_family ] = $this->generate_font_variations( $font_family, $properties['font_weights'], $properties['font_styles'], $properties['file_base'] );
		}
		wp_print_font_faces( $fonts );
	}

	protected function get_font_families(): array {
		return [
			'Open Sans' => [
				'font_weights' => [
					300,
					400,
					500,
					600,
					700,
					800,
				],
				'font_styles'  => [
					'normal',
					'italic',
				],
				'file_base'    => 'OpenSans/open-sans-v40-latin',
			],
		];
	}

	protected function generate_font_variations( string $font_family, array $font_weights, array $font_styles, string $file_base ): array {
		$font_variations = [];
		foreach ( $font_weights as $font_weight ) {
			foreach ( $font_styles as $font_style ) {
				$font_variations[] = [
					'font-family'  => $font_family,
					'font-style'   => $font_style,
					'font-weight'  => $font_weight,
					'font-display' => 'swap',
					'src'          => [
						SIW_ASSETS_URL . "fonts/{$file_base}-{$font_weight}-{$font_style}.woff2",
					],
				];
			}
		}
		return $font_variations;
	}

	#[Add_Action( 'customize_controls_enqueue_scripts' )]
	public function add_customizer_script() {

		wp_register_script( 'siw-customizer-fonts', SIW_ASSETS_URL . 'js/admin/customizer-fonts.js', [], SIW_PLUGIN_VERSION, true );
		wp_localize_script(
			'siw-customizer-fonts',
			'siw_customizer_fonts',
			array_map(
				fn( string $font_family ): array => [
					'value' => $font_family,
					'label' => $font_family,
				],
				array_keys( $this->get_font_families() )
			)
		);
		wp_enqueue_script( 'siw-customizer-fonts' );
	}
}
