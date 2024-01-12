<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Traits\Assets_Handle;
use SIW\Util\CSS;

/**
 * Class voor animaties
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Custom_404_Page extends Base {

	use Assets_Handle;

	#[Add_Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {

		if ( ! is_404() ) {
			return;
		}

		wp_register_style( self::get_assets_handle(), SIW_ASSETS_URL . 'css/features/custom-404-page.css', [], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::get_assets_handle(), 'path', SIW_ASSETS_DIR . 'css/features/custom-404-page.css' );
		wp_enqueue_style( self::get_assets_handle() );

		$logo_url = wp_get_attachment_image_url( get_theme_mod( 'siw_404_background_image' ), 'full' );

		$css = CSS::get_css_generator()->root_variable( 'siw-404-background-url', "url('{$logo_url}')" )->get_output();
		wp_add_inline_style( self::get_assets_handle(), $css );
	}

	#[Add_Action( 'customize_register' )]
	public function add_customizer_settings( \WP_Customize_Manager $wp_customize_manager ) {

		if ( ! $wp_customize_manager->get_panel( 'siw_panel' ) ) {
			$wp_customize_manager->add_panel(
				'siw_panel',
				[
					'priority' => 25,
					'title'    => __( 'SIW opties', 'siw' ),
				]
			);
		}

		$wp_customize_manager->add_section(
			'siw_404',
			[
				'title'    => __( '404-pagina', 'siw' ),
				'priority' => 20,
				'panel'    => 'siw_panel',
			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_title',
			[
				'default' => '',

			]
		);
		$wp_customize_manager->add_setting(
			'siw_404_text',
			[
				'default' => '',

			]
		);

		$wp_customize_manager->add_setting(
			'siw_404_background_image',
			[
				'default' => '',
			]
		);

		// Let op! Onderstaande tekst-instellingen zullen niet goed werken met een meertalige website
		$wp_customize_manager->add_control(
			'siw_404_title',
			[
				'label'       => __( 'Titel', 'siw' ),
				'type'        => 'text',
				'input_attrs' => [
					'placeholder' => __( 'Oops! That page can&rsquo;t be found.', 'generatepress' ), // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
				],
				'section'     => 'siw_404',
			]
		);

		$wp_customize_manager->add_control(
			'siw_404_text',
			[
				'label'       => __( 'Tekst', 'siw' ),
				'type'        => 'textarea',
				'input_attrs' => [
					'placeholder' => __( 'It looks like nothing was found at this location. Maybe try searching?', 'generatepress' ), // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
				],
				'section'     => 'siw_404',
			]
		);

		$wp_customize_manager->add_control(
			new \WP_Customize_Media_Control(
				$wp_customize_manager,
				'siw_404_background_image',
				[
					'label'     => __( 'Achtergrondafbeelding', 'siw' ),
					'section'   => 'siw_404',
					'mime_type' => 'image',
				]
			)
		);
	}

	#[Add_Filter( 'generate_404_title' )]
	public function set_404_title( string $title ): string {
		$custom_title = get_theme_mod( 'siw_404_title' );
		if ( ! empty( $custom_title ) ) {
			return $custom_title;
		}
		return $title;
	}

	#[Add_Filter( 'generate_404_text' )]
	public function set_404_text( string $text ): string {
		$custom_text = get_theme_mod( 'siw_404_text' );
		if ( ! empty( $custom_text ) ) {
			return $custom_text;
		}
		return $text;
	}

	#[Add_Filter( 'generate_blog_columns' )]
	public function disable_columns( bool $columns ): bool {
		if ( is_404() ) {
			return false;
		}
		return $columns;
	}
}
