<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Configuratie van e-mail
 * 
 * - SMTP
 * - DKIM
 * - Afzender
 * 
 * @package   SIW\Email
 * @copyright 2018-2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @uses      SIW_Properties
 */
class SIW_Email_Configuration {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'phpmailer_init', [ $self, 'set_smtp_configuration' ], PHP_INT_MAX );
		add_action( 'phpmailer_init', [ $self, 'set_dkim_configuration' ], PHP_INT_MAX );
		add_filter( 'wp_mail_from', [ $self, 'set_mail_from' ] );
		add_filter( 'wp_mail_from_name', [ $self, 'set_mail_from_name' ] );
	}

	/**
	 * Zet SMTP-instellingen
	 *
	 * @param PHPMailer $phpmailer
	 */
	public function set_smtp_configuration( $phpmailer ) {
		/*SMTP-configuratie*/
		if ( siw_get_option( 'smtp_enabled' ) ) {
			$phpmailer->isSMTP();
			$smtp_settings = siw_get_option( 'smtp_settings');
			$phpmailer->Host = $smtp_settings['host'];
			$phpmailer->Port = $smtp_settings['port'];
			$phpmailer->SMTPAuth = (bool) $smtp_settings['authentication'];
			$phpmailer->Username = $smtp_settings['username'];
			$phpmailer->Password = $smtp_settings['password'];
			$phpmailer->SMTPSecure = $smtp_settings['encryption'];
			$phpmailer->Sender = $phpmailer->From;
		}
	}

	/**
	 * Zet DKIM-signing
	 *
	 * @param PHPMailer $phpmailer
	 */
	public function set_dkim_configuration( $phpmailer ) {
		if ( siw_get_option( 'dkim_enabled' ) && defined( 'SIW_DKIM_KEY' ) ) {
			$dkim_settings = siw_get_option( 'dkim_settings');
			$phpmailer->DKIM_selector = $dkim_settings['selector'];
			$phpmailer->DKIM_domain = $dkim_settings['domain'];
			$phpmailer->DKIM_identity = $phpmailer->From;
			$phpmailer->DKIM_passphrase = $dkim_settings['passphrase'];
			$phpmailer->DKIM_private_string = SIW_DKIM_KEY;
		}
	}

	/**
	 * Zet het afzenderadres (indien nog niet gezet)
	 *
	 * @param string $from
	 * @return string
	 */
	public function set_mail_from( $from ) {
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$default_from = 'wordpress@' . $sitename;
	
		if ( $from != $default_from ) {
			return $from;
		}
	
		return SIW_Properties::EMAIL;
	}

	/**
	 * Zet de afzendernaam (indien nog niet gezet)
	 *
	 * @param string $from_name
	 * @return string
	 */
	public function set_mail_from_name( $from_name ) {
		$default_from_name = 'WordPress';
		if ( $from_name != $default_from_name ) {
			return $from_name;
		}
	
		return SIW_Properties::NAME;
	}
}
