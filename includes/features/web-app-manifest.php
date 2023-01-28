<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Attributes\Action;
use SIW\Attributes\Filter;
use SIW\Base;
use SIW\Properties;
use SIW\Util\CSS;

/**
 * Voegt Web App Manifest toe
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 * @see https://developer.mozilla.org/en-US/docs/Web/Manifest
 */
class Web_App_Manifest extends Base {

	/** Bestandsnaam van web app manifest */
	const WEB_APP_MANIFEST_FILENAME = 'manifest.json';

	#[Filter( 'site_icon_meta_tags' )]
	/** Voegt tag voor web app manifest toe */
	public function add_manifest_tag( array $meta_tags ): array {
		$meta_tags[] = sprintf( '<link rel="manifest" href="%s" crossorigin="use-credentials">', wp_make_link_relative( get_home_url( null, self::WEB_APP_MANIFEST_FILENAME ) ) );
		return $meta_tags;
	}

	#[Action( 'init' )]
	/** Toont web app manifest */
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
			'theme_color'      => CSS::ACCENT_COLOR,
			'background_color' => CSS::BASE_COLOR,
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
