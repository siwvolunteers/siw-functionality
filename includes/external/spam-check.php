<?php declare(strict_types=1);

namespace SIW\External;

use SIW\Helpers\HTTP_Request;
use SIW\Util\Logger;

/**
 * Opzoeken e-mailadres en IP in SFS-spamdatabase
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @link      https://www.stopforumspam.com/usage
 */
class Spam_Check{

	/** API URL */
	const API_URL = 'https://europe.stopforumspam.org/api';

	/** Grens voor Spam TODO: configurabel maken*/
	const SPAM_THRESHOLD = 85.00;

	/** Geldigheidsduur van transient */
	const TRANSIENT_EXPIRATION = 1 * DAY_IN_SECONDS;

	/**Algoritme om email-adress te hashen voor opslag */
	const HASH_ALGORITHM = 'sha1';

	/** Geeft aan of IP gecheckt moet worden */
	protected bool $check_ip = false;

	/** Geeft aan of e-mail gecheckt moet worden */
	protected bool $check_email = false;

	/** IP-adres */
	protected string $ip;

	/** E-mailadres */
	protected string $email;

	/** Hash van e-mailadres */
	protected string $email_hash;

	/** Zet IP-adres om te controlen */
	public function set_ip( string $ip ) {
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			$this->ip = $ip;
			$this->check_ip = true;
		}
	}

	/** Zet e-mailadres om te controleren */
	public function set_email( string $email ) {
		if ( is_email( $email ) ) {
			$this->email = strtolower( $email );
			$this->email_hash = hash( self::HASH_ALGORITHM, $this->email );
			$this->check_email = true;
		}
	}

	/** Geeft aan of het een spammer betreft */
	public function is_spammer() : bool {

		//Afbreken als er niets to controleren is
		if ( ! $this->check_email && ! $this->check_ip ) {
			return false;
		}

		// Check of IP-adres uit transient beschikbaar is en direct afbreken als het vrijwel zeker een spammer is
		if ( $this->check_ip ) {
			$ip_confidence = get_transient( "siw_spam_ip_{$this->ip}" );
			if ( false !== $ip_confidence ) {
				if ( floatval( $ip_confidence ) > self::SPAM_THRESHOLD ) {
					Logger::info( "Gefilterd als spam: ip {$this->ip} (email {$this->email})", 'siw-spam-check' );
					return true;
				}
				$this->check_ip = false;
			}
		}

		// Check of email-adres uit transient beschikbaar is en direct afbreken als het vrijwel zeker een spammer is
		if ( $this->check_email ) {
			$email_confidence = get_transient( "siw_spam_email_{$this->email_hash}" );
			if ( false !== $email_confidence ) {
				if ( floatval( $email_confidence ) > self::SPAM_THRESHOLD ) {
					Logger::info( "Gefilterd als spam: email {$this->email} (ip {$this->ip})", 'siw-spam-check' );
					return true;
				}
				$this->check_email = false;
			}
		}

		// Externe check aanroepen indien nog nodig
		if ( $this->check_email || $this->check_ip ) {
			$result = $this->external_lookup();

			if ( isset( $result['email'] ) ) {
				$email_confidence = floatval( $result['email'] ); 
				set_transient( "siw_spam_email_{$this->email_hash}", $email_confidence, self::TRANSIENT_EXPIRATION );
			}

			if ( isset( $result['ip'] ) ) {
				$email_confidence = floatval( $result['ip'] );
				set_transient( "siw_spam_ip_{$this->ip}", $ip_confidence, self::TRANSIENT_EXPIRATION );
			}
	
			//Bepaal of het een spammer betreft
			if ( isset( $email_confidence ) && $email_confidence > self::SPAM_THRESHOLD ) {
				Logger::info( "Gefilterd als spam: email {$this->email} (ip {$this->ip})", 'siw-spam-check' );
				return true;
			}
			
			if ( isset( $ip_confidence ) && $ip_confidence > self::SPAM_THRESHOLD ) {
				Logger::info( "Gefilterd als spam: ip {$this->ip} (email {$this->email})", 'siw-spam-check' );
				return true;
			}
		}
		
		Logger::info( "Niet gefilterd als spam: email {$this->email} en IP {$this->ip}", 'siw-spam-check' );
		return false;
	}

	/** Zoek email en IP op in externe database */
	protected function external_lookup() : array {

		$body = [
			'json'  => true
		];
		if ( $this->check_email ) {
			$body['email'] = urlencode( $this->email );
		}
		if ( $this->check_ip ) {
			$body['ip'] = $this->ip;
		}

		$request = new HTTP_Request( self::API_URL );
		$request->set_content_type( HTTP_Request::APPLICATION_X_WWW_FORM_URLENCODED );
		$response = $request->post( $body );

		if ( is_wp_error( $response ) || false == $response['success'] ) {
			return [];
		}

		$result = [];
		if ( $this->check_email && $response['email']['appears'] ) {
			$result['email'] = $response['email']['confidence'];
		}

		if ( $this->check_ip && $response['ip']['appears'] ) {
			$result['ip'] = $response['ip']['confidence'];
		}

		return $result;
	}
}
