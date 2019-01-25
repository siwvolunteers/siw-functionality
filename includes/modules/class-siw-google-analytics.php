<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Google Analytics integratie
 * 
 * @package   SIW\Modules
 * @author    Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * 
 * @uses      SIW_Enhanced_Ecommerce
 */
class SIW_Google_Analytics {

	/**
	 * Google Analytics property ID
	 *
	 * @var string
	 */
	protected $property_id;

	/**
	 * Geeft aan of de scripts in de footer geplaatst moeten worden
	 *
	 * @var boolean
	 */
	protected $in_footer = true;

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function init() {
		$self = new self();
		$self->set_property_id();

		if ( false == $self->tracking_enabled() ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ] );
	}


	/**
	 * Undocumented function
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'google-analytics', 'https://www.google-analytics.com/analytics.js', null, null, $this->in_footer );
		wp_scripts()->add_data( 'google-analytics', 'defer', true );
		wp_scripts()->add_data( 'google-analytics', 'async', true );
		wp_enqueue_script( 'siw-analytics', SIW_ASSETS_URL . 'js/siw-analytics.js', [ 'google-analytics', 'jquery' ], SIW_PLUGIN_VERSION, $this->in_footer );

		ob_start();
		?>
			window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
			ga('create','<?= esc_js( $this->property_id ); ?>',{'siteSpeedSampleRate': 100});
			ga('set', 'anonymizeIp', true);
			ga('set', 'forceSSL', true);
			ga('send','pageview');
		<?php
		$snippet = ob_get_clean();
		wp_add_inline_script( 'google-analytics', $snippet, 'before' );

		return;
	}

	/**
	 * Haalt het GA property ID op
	 *
	 * @return void
	 */
	protected function set_property_id() {
		$this->property_id = siw_get_setting( 'google_analytics_id' );
		return $this;
	}

	/**
	 * Geeft aan of tracking ingeschakeld moet worden
	 *
	 * @return void
	 */
	protected function tracking_enabled() {
		if ( ! isset( $this->property_id ) || is_user_logged_in() ) {
			return false;
		}
		return true;
	}
}
