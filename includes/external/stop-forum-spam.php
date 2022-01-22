<?php declare(strict_types=1);

namespace SIW\External;

use Noj\Dot\Dot;
use SIW\Helpers\HTTP_Request;

/**
 * Opzoeken e-mailadres en IP in SFS-spamdatabase
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @link      https://www.stopforumspam.com/usage
 */
class Stop_Forum_Spam {

	/** Grens voor Spam TODO: configurabel maken*/
	const SPAM_THRESHOLD = 85.00;

	/** API URL */
	const API_URL = 'https://europe.stopforumspam.org/api';

	/** E-mailadres om te checken */
	protected string $email;

	/** IP-adres om te checken */
	protected string $ip;

	/** Zet e-mailadres om te checken */
	public function set_email( string $email ) {
		$this->email = $email;
	}

	/** Zet IP-adres om te checken */
	public function set_ip( string $ip ) {
		$this->ip = $ip;
	}

	/** Check ip en e-mail bij SFS */
	public function check(): Dot {
		$body = [
			'json' => true
		];
		if ( isset( $this->email ) ) {
			$body['email'] = urlencode( $this->email );
		}
		if ( isset( $this->ip ) ) {
			$body['ip'] = $this->ip;
		}

		$response = HTTP_Request::create( self::API_URL )
			->set_content_type( HTTP_Request::APPLICATION_X_WWW_FORM_URLENCODED )
			->post( $body );

		if ( is_wp_error( $response ) || false == $response['success'] ) {
			return new Dot([]);
		}

		$response = new Dot( $response );

		$results = new Dot([]);
		if ( (bool) $response->has( 'email.confidence' ) ) {
			$results->set('email', (float) $response->get( 'email.confidence') > self::SPAM_THRESHOLD);
		}

		if ( (bool) $response->has( 'ip.confidence' ) ) {
			$results->set('ip', (float) $response->get( 'ip.confidence') > self::SPAM_THRESHOLD);
		}

		return $results;
	}
}

