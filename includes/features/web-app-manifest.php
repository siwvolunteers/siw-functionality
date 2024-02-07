<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Data\Color;
use SIW\Properties;

/**
 * @see https://developer.mozilla.org/en-US/docs/Web/Manifest
 */
class Web_App_Manifest extends Base {

	private const WEB_APP_MANIFEST_FILENAME = 'manifest.json';

	#[Add_Filter( 'site_icon_meta_tags' )]
	public function add_manifest_tag( array $meta_tags ): array {
		$meta_tags[] = sprintf(
			'<link rel="manifest" href="%s" crossorigin="use-credentials">',
			wp_make_link_relative( get_home_url( null, self::WEB_APP_MANIFEST_FILENAME ) )
		);
		return $meta_tags;
	}

	#[Add_Action( 'init' )]
	public function show_web_app_manifest() {
		$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( ! str_ends_with( $request, self::WEB_APP_MANIFEST_FILENAME ) ) {
			return;
		}

		$data = [
			'short_name'       => 'SIW',
			'name'             => Properties::NAME,
			'description'      => esc_attr( get_bloginfo( 'description' ) ),
			'lang'             => str_replace( '_', '-', get_locale() ),
			'start_url'        => '.',
			'scope'            => '/',
			'display'          => 'browser',
			'orientation'      => 'any',
			'dir'              => 'ltr',
			'theme_color'      => Color::ACCENT->color(),
			'background_color' => Color::BASE->color(),
			'icons'            => [
				[
					'src'   => get_site_icon_url( 192 ),
					'sizes' => '192x192',
				],
				[
					'src'   => get_site_icon_url( 512 ),
					'sizes' => '512x512',
				],
			],
		];
		wp_send_json( $data, \WP_Http::OK );
	}
}
