<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Base;
use SIW\External_Assets\Iframe_Manager as Iframe_Manager_Asset;
use SIW\Traits\Class_Assets;
use SIW\Util\Links;

class Iframe_Manager extends Base {

	use Class_Assets;

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_styles() {
		self::enqueue_class_style( [ Iframe_Manager_Asset::get_asset_handle() ] );
	}

	#[Add_Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		wp_register_script(
			self::get_asset_handle(),
			self::get_script_asset_url(),
			[ Iframe_Manager_Asset::get_asset_handle() ],
			SIW_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			self::get_asset_handle(),
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
										sprintf( __( 'Door de video te laden, accepteer je de %s van YouTube', 'siw' ), Links::generate_external_link( 'https://www.youtube.com/t/terms', __( 'algemene voorwaarden', 'siw' ) ) ),
									'loadBtn'    => __( 'Deze video laden', 'siw' ),
									'loadAllBtn' => __( 'Vraag het niet opnieuw', 'siw' ),
								],
							],
						],
					],
				],

			]
		);

		wp_enqueue_script( self::get_asset_handle() );
	}
}
