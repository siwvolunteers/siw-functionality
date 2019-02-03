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
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
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
		if ( siw_get_setting( 'smtp_enabled' ) ) {
			$phpmailer->isSMTP();
			$phpmailer->Host = siw_get_setting( 'smtp_host' );
			$phpmailer->Port = siw_get_setting( 'smtp_port');
			$phpmailer->SMTPAuth = (bool) siw_get_setting( 'smtp_authentication' );
			$phpmailer->Username = siw_get_setting( 'smtp_credentials' )['username'];
			$phpmailer->Password = siw_get_setting( 'smtp_credentials' )['password'];
			$phpmailer->SMTPSecure = siw_get_setting( 'smtp_encryption');
			$phpmailer->Sender = $phpmailer->From;
		}
	}

	/**
	 * Zet DKIM-signing
	 *
	 * @param PHPMailer $phpmailer
	 */
	public function set_dkim_configuration( $phpmailer ) {
		if ( ! defined( 'SIW_DKIM_KEY' ) ) {
			define( 'SIW_DKIM_KEY', false );
		}

		if ( siw_get_setting( 'dkim_enabled' ) && SIW_DKIM_KEY ) {
			$phpmailer->DKIM_selector = siw_get_setting( 'dkim_selector' );
			$phpmailer->DKIM_domain = siw_get_setting( 'dkim_domain' );
			$phpmailer->DKIM_identity = $phpmailer->From;
			$phpmailer->DKIM_passphrase = siw_get_setting( 'dkim_passphrase' );
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