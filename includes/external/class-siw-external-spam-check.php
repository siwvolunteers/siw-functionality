<?php

/**
 * Opzoeken e-mailadres en IP in SFS-spamdatabase
 *
 * @package   SIW\External
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 * 
 * @link      https://www.stopforumspam.com/usage
 */
class SIW_External_Spam_Check{

	/**
	 * Grens voor Spam
	 *
	 * @var float
	 */
	const SPAM_THRESHOLD = 90.00;

	/**
	 * Geldigheidsduur van transient
	 *
	 * @var int
	 */
	const TRANSIENT_EXPIRATION = 1 * DAY_IN_SECONDS;

	/**
	 * Algoritme om email-adress te hashen voor opslag
	 * 
	 * @var string
	 */
	const HASH_ALGORITHM = 'sha1';

	/**
	 * Geeft aan of IP gecheckt moet worden
	 *
	 * @var bool
	 */
	protected $check_ip = false;

	/**
	 * Geeft aan of e-mail gecheckt moet worden
	 *
	 * @var bool
	 */
	protected $check_email = false;

	/**
	 * IP-adres
	 *
	 * @var string
	 */
	protected $ip;

	/**
	 * E-mailadres
	 *
	 * @var string
	 */
	protected $email;

	/**
	 * Hash van e-mailadres
	 *
	 * @var string
	 */
	protected $email_hash;

	/**
	 * Zet IP-adres om te controlen
	 *
	 * @param string $ip
	 */
	public function set_ip( string $ip ) {
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			$this->ip = $ip;
			$this->check_ip = true;
		}
	}

	/**
	 * Zet e-mailadres om te controleren
	 *
	 * @param string $email
	 */
	public function set_email( string $email ) {
		if ( is_email( $email ) ) {
			$this->email = strtolower( $email );
			$this->email_hash = hash( self::HASH_ALGORITHM, $this->email );
			$this->check_email = true;
		}
	}

	/**
	 * Geeft aan of het een spammer betreft
	 *
	 * @return bool
	 */
	public function is_spammer() {

		//Afbreken als er niets to controleren is
		if ( false === $this->check_email && false === $this->check_ip ) {
			return false;
		}

		// Check of IP-adres uit transient beschikbaar is en direct afbreken als het vrijwel zeker een spammer is
		if ( $this->check_ip ) {
			$ip_confidence = get_transient( "siw_spam_ip_{$this->ip}" );
			if ( false !== $ip_confidence ) {
				if ( $ip_confidence > self::SPAM_THRESHOLD ) {
					return true;
				}
				$this->check_ip = false;
			}
		}

		// Check of email-adres uit transient beschikbaar is en direct afbreken als het vrijwel zeker een spammer is
		if ( $this->check_email ) {
			$email_confidence = get_transient( "siw_spam_email_{$this->email_hash}" );
			if ( false !== $email_confidence ) {
				if ( $email_confidence > self::SPAM_THRESHOLD ) {
					return true;
				}
				$this->check_email = false;
			}
		}

		// Externe check aanroepen indien nog nodig
		if ( $this->check_email || $this->check_ip ) {
			$result = $this->external_lookup();

			if ( isset( $result['email'] ) ) {
				$email_confidence = $result['email'];
				set_transient( "siw_spam_email_{$this->email_hash}", $result['email'], self::TRANSIENT_EXPIRATION );
			}

			if ( isset( $result['ip'] ) ) {
				$email_confidence = $result['ip'];
				set_transient( "siw_spam_ip_{$this->ip}", $result['ip'], self::TRANSIENT_EXPIRATION );
			}
		}
		
		//Bepaal of het een spammer betreft
		if ( isset( $email_confidence ) && $email_confidence > self::SPAM_THRESHOLD ) {
			return true;
		}
		
		if ( isset( $ip_confidence ) && $ip_confidence > self::SPAM_THRESHOLD ) {
			return true;
		}

		return false;
	}

	/**
	 * Zoek email en IP op in externe database
	 */
	protected function external_lookup() {

		$body = [
			'json'  => true
		];
		if ( $this->check_email ) {
			$body['email'] = $this->email;
		}
		if ( $this->check_ip ) {
			$body['ip'] = $this->ip;
		}

		$args = [
			'body'    => $body,
			'timeout' => 10,
		];

		$response = wp_safe_remote_post( SIW_Properties::SPAM_CHECK_API_URL, $args );

		if ( false == $this->check_response( $response ) ) {
			return [];
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( false == $body['success'] ) {
			return [];
		}

		$result = [];
		if ( $this->check_email && $body['email']['appears'] ) {
			$result['email'] = $body['email']['confidence'];
		}

		if ( $this->check_ip && $body['ip']['appears'] ) {
			$result['ip'] = $body['ip']['confidence'];
		}

		return $result;
	}

	/**
	 * Check HTTP response
	 *
	 * @param WP_Error|array $response
	 * @return bool
	 */
	protected function check_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return false;
		}
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}
		
		return true;
	}
}