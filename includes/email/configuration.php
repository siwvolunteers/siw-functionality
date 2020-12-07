<?php declare(strict_types=1);

namespace SIW\Email;

use SIW\Properties;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Configuratie van e-mail
 * 
 * - SMTP
 * - DKIM
 * - Mailjet tracking
 * - Afzender
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Configuration {

	/**
	 * Init
	 */
	public static function init() {
		$self = new self();
		add_action( 'phpmailer_init', [ $self, 'set_smtp_configuration' ], PHP_INT_MAX );
		add_action( 'phpmailer_init', [ $self, 'set_dkim_configuration' ], PHP_INT_MAX );
		add_action( 'phpmailer_init', [ $self, 'set_mailjet_tracking' ], PHP_INT_MAX );
		add_action( 'phpmailer_init', [ $self, 'set_antispam_header' ], PHP_INT_MAX );
	}

	/**
	 * Zet SMTP-instellingen
	 *
	 * @param PHPMailer $phpmailer
	 */
	public function set_smtp_configuration( PHPMailer $phpmailer ) {
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
	public function set_dkim_configuration( PHPMailer $phpmailer ) {
		if ( siw_get_option( 'dkim_enabled' ) && defined( 'SIW_DKIM_PASSPHRASE' ) ) {
			$dkim_settings = siw_get_option( 'dkim_settings');
			$phpmailer->DKIM_selector = $dkim_settings['selector'];
			$phpmailer->DKIM_domain = $dkim_settings['domain'];
			$phpmailer->DKIM_identity = $phpmailer->From;
			$phpmailer->DKIM_passphrase = defined( 'SIW_DKIM_PASSPHRASE' ) ? SIW_DKIM_PASSPHRASE : '' ;
			$phpmailer->DKIM_private_string = $dkim_settings['key'];
		}
	}

	/**
	 * Zet tracking van Mailjet aan of uit
	 *
	 * @param PHPMailer $phpmailer
	 * 
	 * @todo optie voor maken
	 */
	public function set_mailjet_tracking( PHPMailer $phpmailer ) {
		$phpmailer->addCustomHeader( 'X-Mailjet-TrackOpen', 0 );
		$phpmailer->addCustomHeader( 'X-Mailjet-TrackClick', 0 );
	}
	
	/**
	 * Zet header t.b.v. spamfilter Office-365
	 *
	 * @param PHPMailer $phpmailer
	 *
	 * @todo optie voor waarde maken
	 */
	public function set_antispam_header( PHPMailer $phpmailer ) {
		$phpmailer->addCustomHeader( 'X-SIW-WebsiteMail', 1 );
	}
}
