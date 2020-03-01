<?php

namespace SIW\Compatibility;

use SIW\Properties;
use SIW\Email\Template;

/**
* Aanpassingen voor Mailpoet
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @see       https://www.mailpoet.com/
 * @since     3.0.0
 */
class Mailpoet {

	/**
	 * Init
	 */
	public static function init() {
		if ( ! class_exists( '\WYSIJA' ) ) {
			return;
		}
		$self = new self();
		add_action( 'widgets_init', [ $self, 'unregister_widget' ], 99 );
		add_action( 'wp_enqueue_scripts', [ $self, 'deregister_style' ], PHP_INT_MAX );
		add_action( 'wp_ajax_nopriv_wysija_ajax', [ $self, 'block_signups' ], 1 );
		add_filter( 'wysija_subscription_limit_base', [ $self, 'set_subscription_limit_base' ] );
		add_action( 'siw_update_plugin', [ $self, 'update_confirmation_mail_template'] );
		add_action( 'init', [ $self, 'remove_extra_image_size'] );
	}

	/**
	 * Verwijdert MailPoet 2 Widget
	 */
	public function unregister_widget() {
		unregister_widget( 'WYSIJA_NL_Widget' );
	}

	/**
	 * Verwijdert MailPoet 2 styling in frontend
	 */
	public function deregister_style() {
		wp_deregister_style( 'validate-engine-css' );
	}

	/**
	 * Verwijdert extra image size
	 */
	public function remove_extra_image_size() {
		remove_image_size( 'wysija-newsletters-max' );
	}

	/**
	 * Blokkeert aanmeldingen via standaard MailPoet-widget
	 */
	public function block_signups() {
		/* Mailpoet spam-signups blokkeren */
		$controller = $_POST['controller'];
		$task = $_POST['task'];
		if ( 'subscribers' == $controller && 'save' == $task ) {
			wp_die( '', \WP_Http::FORBIDDEN );
		}
	}

	/**
	 * Zet tijdslimiet voor aantal aanmeldingen van zelfde IP-adres
	 *
	 * @return int
	 */
	public function set_subscription_limit_base() {
		return HOUR_IN_SECONDS;
	}

	/**
	 * Werkt template van bevestigingsmail bij
	 */
	public function update_confirmation_mail_template() {
		$model_config = \WYSIJA::get('config','model');
		$confirm_email_id = $model_config->values['confirm_email_id'];

		$template_args = [
			'subject' => 'Aanmelding nieuwsbrief',
			'message' => 'Beste [user:firstname],'. BR2 .
				'Bedankt voor je aanmelding voor de SIW-nieuwsbrief!' . SPACE .
				'Om zeker te weten dat je inschrijving correct is, vragen we je je aanmelding te bevestigen.' . BR2 .
				'[activation_link]Klik hier om je aanmelding voor onze nieuwsbrief direct te bevestigen[/activation_link]' . BR2 .
				sprintf( 'Tip: voeg %s toe aan je adresboek.', Properties::EMAIL ) . SPACE .
				'Zo mis je nooit meer nieuws over onze infodagen, ervaringsverhalen of projecten.',
			'show_signature'    => true,
			'signature_name'    => 'De vrijwilligers van SIW', 'siw',
			'remove_linebreaks' => true,
		];

		$template = new Template( $template_args );

		global $wpdb;
		if ( ! isset( $wpdb->wysija_email ) ) {
			$wpdb->wysija_email = $wpdb->prefix . 'wysija_email';
		}
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $wpdb->wysija_email
				SET body = %s
					WHERE $wpdb->wysija_email.email_id  = %d",
				$template->generate(),
				$confirm_email_id
			)
		);
	}
}
