<?php declare(strict_types=1);

namespace SIW\Features;

use SIW\Properties;

use PHPMailer\PHPMailer\PHPMailer;
use SIW\Attributes\Add_Action;
use SIW\Attributes\Add_Filter;
use SIW\Base;
use SIW\Config;

class Email extends Base {

	#[Add_Action( 'phpmailer_init', PHP_INT_MAX )]
	public function set_smtp_configuration( PHPMailer $phpmailer ) {
		if ( Config::get_smtp_enabled() ) {
			$phpmailer->isSMTP();
			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->Host = Config::get_smtp_host();
			$phpmailer->Port = Config::get_smtp_port();
			$phpmailer->SMTPAuth = null !== Config::get_smtp_username() && null !== Config::get_smtp_password();
			$phpmailer->Username = Config::get_smtp_username();
			$phpmailer->Password = Config::get_smtp_password();

			$phpmailer->SMTPSecure = in_array( Config::get_smtp_encryption(), [ PHPMailer::ENCRYPTION_SMTPS, PHPMailer::ENCRYPTION_STARTTLS ], true ) ? Config::get_smtp_encryption() : '';
			$phpmailer->Sender = $phpmailer->From;
			// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
	}

	#[Add_Action( 'phpmailer_init', PHP_INT_MAX )]
	public function set_dkim_configuration( PHPMailer $phpmailer ) {
		if ( Config::get_dkim_enabled() ) {
			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->DKIM_selector = Config::get_dkim_selector();
			$phpmailer->DKIM_domain = Config::get_dkim_domain();
			$phpmailer->DKIM_identity = $phpmailer->From;
			$phpmailer->DKIM_passphrase = Config::get_dkim_passphrase();
			$phpmailer->DKIM_private = Config::get_dkim_private_key_file_path();
			// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
	}

	#[Add_Action( 'phpmailer_init', PHP_INT_MAX )]
	public function set_mailjet_tracking( PHPMailer $phpmailer ) {
		//Zet tracking van Mailjet uit TODO: config van maken
		$phpmailer->addCustomHeader( 'X-Mailjet-TrackOpen', 0 );
		$phpmailer->addCustomHeader( 'X-Mailjet-TrackClick', 0 );
	}

	#[Add_Action( 'phpmailer_init', PHP_INT_MAX )]
	public function set_antispam_header( PHPMailer $phpmailer ) {
		// Zet header t.b.v. spamfilter Office-365
		$phpmailer->addCustomHeader( 'X-SIW-WebsiteMail', 1 );
	}

	#[Add_Filter( 'wp_mail_from' )]
	public function set_mail_from( string $from ): string {
		$sitename = strtolower( SIW_SITE_NAME );
		if ( substr( $sitename, 0, 4 ) === 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}
		$default_from = 'wordpress@' . $sitename;

		if ( $from !== $default_from ) {
			return $from;
		}
		return Properties::EMAIL;
	}

	#[Add_Filter( 'wp_mail_from_name' )]
	public function set_mail_from_name( string $from_name ): string {
		$default_from_name = 'WordPress';
		if ( $from_name !== $default_from_name ) {
			return $from_name;
		}
		return Properties::NAME;
	}
}
