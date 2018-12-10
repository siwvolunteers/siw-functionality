<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Google Analytics integratie
 * 
 * @package SIW\Analytics
 * @author Maarten Bruna
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 */
class SIW_Analytics {

	/**
	 * Undocumented variable
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

		if ( true == $self->disable_tracking() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $self, 'enqueue_scripts' ] );
	}


	/**
	 * Undocumented function
	 *
	 * @return void
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
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function set_property_id() {
		$this->property_id = siw_get_setting( 'google_analytics_id' );
		return $this;
	}

	protected function disable_tracking() {
		if ( ! isset( $this->property_id ) ) {
			return true;
		}

		/*Geen GA voor ingelogde gebruikers*/
		if ( is_user_logged_in() ) {
			return true;
		}

		return false;
	}

}