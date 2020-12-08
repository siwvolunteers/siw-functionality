<?php declare(strict_types=1);

namespace SIW\API;

use SIW\External\Spam_Check as External_Spam_Check;

/**
 * API endpoint voor aanmelding nieuwsbrief
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 */
class Newsletter_Subscribe extends Endpoint {

	/**
	 * {@inheritDoc}
	 */
	protected string $resource = 'newsletter_subscribe';

	/**
	 * {@inheritDoc}
	 */
	protected string $callback = 'process';

	/**
	 * {@inheritDoc}
	 */
	protected array $methods = [ \WP_REST_Server::CREATABLE] ;

	/**
	 * {@inheritDoc}
	 */
	protected string $script = 'newsletter';

	/**
	 * {@inheritDoc}
	 */
	protected function set_parameters() {
		$this->parameters = [
			'name'  => true,
			'email' => true,
		];
	}

	/**
	 * Valideert naam
	 *
	 * @param mixed $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_name( string $param, \WP_REST_Request $request, string $key ) : bool {
		return is_string( $param );
	}

	/**
	 * Formatteert naam
	 *
	 * @param mixed $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_name( string $param, \WP_REST_Request $request, string $key ) : string {
		return sanitize_text_field( $param );
	}

	/**
	 * Valideert e-mail
	 *
	 * @param string $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_email( string $param, \WP_REST_Request $request, string $key ) : bool {
		return is_string( is_email( $param ) );
	}

	/**
	 * Formatteert email
	 *
	 * @param string $param
	 * @param \WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_email( string $param, \WP_REST_Request $request, string $key ) : string {
		return sanitize_email( $param );
	}

	/**
	 * Verwerk verzoek
	 *
	 * @param \WP_REST_Request $request
	 * @return \WP_REST_Response
	 */
	public function process( \WP_REST_Request $request ) : \WP_REST_Response {

		$first_name = $request->get_param( 'name' );
		$email = $request->get_param( 'email' );

		if ( $this->is_blocked_domain( $email ) ) {
			return new \WP_REST_Response( [
				'success' => false,
				'message' => __( 'Het is niet mogelijk om je aan te melden met dit e-mailadres.', 'siw' ),
			], \WP_Http::OK );
		}
		
		//Spam check
		$spam_check = new External_Spam_Check();
		$spam_check->set_email( $email );
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ){
			$spam_check->set_ip( $_SERVER['REMOTE_ADDR']);
		}
	
		if ( $spam_check->is_spammer() ) {
			return new \WP_REST_Response( [
				'success' => false,
				'message' => __( 'Het is niet mogelijk om je aan te melden met dit e-mailadres.', 'siw' ),
			], \WP_Http::OK );
		}

		if ( true === $this->subscribe( $first_name, $email ) ) {
			return new \WP_REST_Response( [
				'success' => true,
				'message' => __( 'Je bent er bijna! Check je inbox voor de bevestigingsmail om je aanmelding voor de nieuwsbrief te bevestigen.', 'siw' ),
			], \WP_Http::OK );
		}

		return new \WP_REST_Response( [
			'success' => false,
			'message' => __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		], \WP_Http::OK );
	}

	/**
	 * Verwerk de aanmelding
	 *
	 * @param string $first_name
	 * @param string $email
	 * 
	 * @return bool
	 */
	protected function subscribe( string $first_name, string $email ) {

		$result = siw_newsletter_send_confirmation_email(
			$email,
			siw_get_option( 'newsletter_list' ),
			[
				'firstname' => $first_name
			]
		);
		return $result;
	}

	/**
	 * Geeft aan of het emailadres van een geblokkeerd domein komt
	 *
	 * @param string $email
	 * @return bool
	 */
	public function is_blocked_domain( string $email ) {
		$blocked_domains = siw_get_option( 'blocked_domains', [] );
		$domain = substr( $email, strrpos( $email, '@' ) + 1 );

		return in_array( $domain, $blocked_domains );
	}
}
