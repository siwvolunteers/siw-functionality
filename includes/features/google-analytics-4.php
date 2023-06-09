<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Assets\Google_Analytics_4 as Google_Analytics_4_Asset;
use SIW\Attributes\Action;
use SIW\Base;
use SIW\Config;

/**
 * Google Maps
 *
 * @copyright 2023 SIW Internationale Vrijwilligersprojecten
 *
 * @link      https://developers.google.com/analytics/devguides/collection/ga4
 */
class Google_Analytics_4 extends Base {

	const ASSETS_HANDLE = 'siw-analytics-4';

	private array $config_settings = [
		'allow_ad_personalization_signals' => false,
		'allow_google_signals'             => false,
		'debug_mode'                       => WP_DEBUG ? true : null,
	];

	#[Action( 'wp_enqueue_scripts' )]
	public function enqueue_scripts() {
		if ( is_user_logged_in() || null === Config::get_google_analytics_measurement_id() ) {
			return;
		}
		wp_register_script( self::ASSETS_HANDLE, SIW_ASSETS_URL . 'js/features/google-analytics-4.js', [ Google_Analytics_4_Asset::ASSETS_HANDLE ], SIW_PLUGIN_VERSION, true );
		wp_localize_script( self::ASSETS_HANDLE, 'siw_google_analytics_4', $this->generate_analytics_data() );
		wp_enqueue_script( self::ASSETS_HANDLE );
	}

	protected function generate_analytics_data(): array {
		$analytics_data['measurement_id'] = Config::get_google_analytics_measurement_id();
		$analytics_data['config_settings'] = array_filter( $this->config_settings, fn( mixed $value ) => null !== $value );
		return array_filter( $analytics_data );
	}
}
