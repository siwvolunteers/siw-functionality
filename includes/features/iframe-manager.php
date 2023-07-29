<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Action;
use SIW\Base;
use SIW\External_Assets\Iframe_Manager as Iframe_Manager_Asset;
use SIW\Util\Links;

/**
 * Cookie consent
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 */
class Iframe_Manager extends Base {

	const ASSETS_HANDLE = 'siw-iframe-manager';

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_styles() {
		wp_register_style( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'css/features/iframe-manager.css', [ Iframe_Manager_Asset::get_assets_handle() ], SIW_PLUGIN_VERSION );
		wp_style_add_data( self::ASSETS_HANDLE, 'path', SIW_ASSETS_DIR . 'css/iframe-manager.css' );
		wp_enqueue_style( self::ASSETS_HANDLE );
	}

	#[Action( 'wp_enqueue_scripts' )]
	/** Voegt stylesheet toe */
	public function enqueue_scripts() {
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/features/iframe-manager.js', [ Iframe_Manager_Asset::get_assets_handle() ], SIW_PLUGIN_VERSION, true );

		wp_localize_script(
			self::ASSETS_HANDLE,
			'siw_iframe_manager',
			[
				'config' => [
					'currLang' => determine_locale(),
					'services' => [
						'youtube' => [
							'embedUrl'     => 'https://www.youtube-nocookie.com/embed/{data-id}',
							'thumbnailUrl' => 'https://i3.ytimg.com/vi/{data-id}/hqdefault.jpg',
							'iframe'       => [
								'allow' => 'accelerometer; encrypted-media; gyroscope; picture-in-picture; fullscreen;',
							],
							'cookie'       => [
								'name'     => 'siw_youtube_consent',
								'sameSite' => 'Strict',
							],
							'languages'    => [
								determine_locale() => [
									'notice'     =>
										__( 'Deze inhoud wordt gehost door een derde partij.', 'siw' ) . SPACE .
										// translators: %s is de link naar de algemene voorwaarden van YouTube
										sprintf( __( 'Door de externe inhoud te tonen, accepteer je de %s van youtube.com.', 'siw' ), Links::generate_external_link( 'https://www.youtube.com/t/terms', __( 'algemene voorwaarden', 'siw' ) ) ),
									'loadBtn'    => __( 'Video laden', 'siw' ),
									'loadAllBtn' => __( 'Vraag het niet opnieuw', 'siw' ),
								],
							],
						],
					],
				],

			]
		);

		wp_enqueue_script( self::ASSETS_HANDLE );
	}
}

