<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
* Aanpassingen voor Mailpoet
 * 
 * @package     SIW\Compatibility
 * @copyright   2018 SIW Internationale Vrijwilligersprojecten
 * @author      Maarten Bruna
 */

class SIW_Mailpoet {

	/**
	 * Init
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! class_exists( 'WYSIJA' ) ) {
			return;
		}
		$self = new self();
		add_action( 'widgets_init', [ $self, 'unregister_widget' ], PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', [ $self, 'deregister_style' ], PHP_INT_MAX );
		add_action( 'wp_ajax_nopriv_wysija_ajax', [ $self, 'block_signups' ], 1 );
		add_filter( 'wysija_subscription_limit_base', [ $self, 'set_subscription_limit_base' ] );
	}

	/**
	 * Verwijdert MailPoet 2 Widget
	 *
	 * @return void
	 */
	public function unregister_widget() {
		unregister_widget( 'WYSIJA_NL_Widget' );
	}

	/**
	 * Verwijdert MailPoet 2 styling in frontend
	 *
	 * @return void
	 */
	public function deregister_style() {
		wp_deregister_style( 'validate-engine-css' );
	}

	/**
	 * Blokkeert aanmeldingen via standaard MailPoet-widget
	 *
	 * @return void
	 */
	public function block_signups() {
		/* Mailpoet spam-signups blokkeren */
		$controller = $_POST['controller'];
		$task = $_POST['task'];
		if ( 'subscribers' == $controller && 'save' == $task ) {
			wp_die( '', 403 );
		}
	}

	/**
	 * Zet tijdslimiet voor aantal aanmeldingen van zelfde IP-adres
	 *
	 * @param int $limit_base
	 * @return int
	 */
	public function set_subscription_limit_base( $limit_base ) {
		$limit_base = HOUR_IN_SECONDS;
		return $limit_base;
	}
}