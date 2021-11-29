<?php declare(strict_types=1);

namespace SIW\Helpers;

use SIW\External\Stop_Forum_Spam;
use SIW\Util\Logger;

/**
 * Class om een spam check uit te voeren
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
class Spam_Check {

	/** Geldigheidsduur van transient */
	const TRANSIENT_EXPIRATION = 1; // * DAY_IN_SECONDS;

	/**Algoritme om email-adress te hashen voor opslag */
	const HASH_ALGORITHM = 'sha1';

	const LOGGER_CONTEXT = 'siw-spam-check';

	/** IP-adres om te checken */
	protected string $ip;

	/** E-mailadres om te checken */
	protected string $email;

	/** Hash van e-mailadres (voor transient) */
	protected string $email_hash;

	/** Bericht om te checken */
	protected string $message;

	/** Geeft aan of IP gecheckt moet worden */
	protected bool $check_ip = false;

	/** Geeft aan of e-mail gecheckt moet worden */
	protected bool $check_email = false;

	/** Protected constructor */
	protected function __construct() {}

	/** Maak request aan */
	public static function create(): self {
		$self = new self();
		return $self;
	}

	/** Zet IP-adres om te controlen */
	public function set_ip( string $ip ): self {
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			$this->ip = $ip;
			$this->check_ip = true;
		}
		return $this;
	}

	/** Zet e-mailadres om te controleren */
	public function set_email( string $email ): self {
		if ( is_email( $email ) ) {
			$this->email = strtolower( $email );
			$this->email_hash = hash( self::HASH_ALGORITHM, $this->email );
			$this->check_email = true;
		}
		return $this;
	}

	/** Zet bericht om te controlen */
	public function set_message( string $message ): self {
		$this->message = $message;
		return $this;
	}

	/** Check of het om spam gaat */
	public function is_spam(): bool {

		//Check message (op links e.d.)
		

		// Check of IP-adres uit transient beschikbaar is en direct afbreken als het vrijwel zeker een spammer is
		if ( $this->check_ip ) {
			$ip_spam = false ;// get_transient( "siw_spam_ip_{$this->ip}" );
			if ( false !== $ip_spam ) {
				if ( (boolean) $ip_spam ) {
					Logger::info( "Gefilterd als spam: ip {$this->ip}", self::LOGGER_CONTEXT );
					return true;
				}
				$this->check_ip = false;
			}
		}

		// Check of email-adres uit transient beschikbaar is en direct afbreken als het vrijwel zeker een spammer is
		if ( $this->check_email ) {
			$email_is_spam = false;// get_transient( "siw_spam_email_{$this->email_hash}" );
			if ( false !== $email_is_spam ) {
				if ( (bool) $email_is_spam ) {
					Logger::info( "Gefilterd als spam: email {$this->email}", self::LOGGER_CONTEXT );
					return true;
				}
				$this->check_email = false;
			}
		}

		if ( ! $this->check_ip && ! $this->check_email ) {
			return false;
		}

		//Externe spamcheck bij StopForumSpam
		$sfs = new Stop_Forum_Spam();
		if ( $this->check_email ) {
			$sfs->set_email( $this->email );
		}
		if ( $this->check_ip ) {
			$sfs->set_ip( $this->ip );
		}
		$result = $sfs->check();
		if ( $result->has( 'email' ) ) {
			set_transient( "siw_spam_email_{$this->email_hash}", (int) $result->get( 'email' ), self::TRANSIENT_EXPIRATION );
		}
		if ( $result->has( 'ip' ) ) {
			set_transient( "siw_spam_ip_{$this->ip}", (int) $result->get( 'ip' ), self::TRANSIENT_EXPIRATION );
		}

		//Bepaal of het een spammer betreft
		if ( $result->get( 'email' ) ) {
			Logger::info( "Gefilterd als spam: email {$this->email}", self::LOGGER_CONTEXT );
			return true;
		}

		if ( $result->get( 'ip' ) ) {
			Logger::info( "Gefilterd als spam: IP {$this->ip}", self::LOGGER_CONTEXT );
			return true;
		}

		return false;
	}
}