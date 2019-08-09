<?php

/**
 * API endpoint voor aanmelding nieuwsbrief
 *
 * @package   SIW\API
 * @copyright 2018 SIW Internationale Vrijwilligersprojecten
 * @author    Maarten Bruna
 */
class SIW_API_Newsletter_Subscribe extends SIW_API {

	/**
	 * {@inheritDoc}
	 */
	protected $resource = 'newsletter_subscribe';

	/**
	 * {@inheritDoc}
	 */
	protected $callback = 'process';

	/**
	 * {@inheritDoc}
	 */
	protected $methods = WP_REST_Server::CREATABLE;


	protected $script = 'newsletter';

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
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_name( string $param, WP_REST_Request $request, string $key ) {
		return is_string( $param );
	}

	/**
	 * Formatteert naam
	 *
	 * @param mixed $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_name( string $param, WP_REST_Request $request, string $key ) {
		return sanitize_text_field( $param );
	}

	/**
	 * Valideert e-mail
	 *
	 * @param string $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_email( string $param, WP_REST_Request $request, string $key ) {
		return is_email( $param );
	}

	/**
	 * Formatteert email
	 *
	 * @param string $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function sanitize_email( string $param, WP_REST_Request $request, string $key ) {
		return sanitize_email( $param );
	}

	/**
	 * Verwerk verzoek
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function process( WP_REST_Request $request ) {

		$first_name = $request->get_param( 'name' );
		$email = $request->get_param( 'email' );

		if ( true === $this->is_blocked_domain( $email ) ) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => __( 'Het is niet mogelijk om je aan te melden met dit e-mailadres.', 'siw' ),
			], 200 );
		}
		
		//Spam check
		$spam_check = new SIW_External_Spam_Check();
		$spam_check->set_email( $email );
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ){
			$spam_check->set_ip( $_SERVER['REMOTE_ADDR']);
		}
	
		if ( true === $spam_check->is_spammer() ) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => __( 'Het is niet mogelijk om je aan te melden met dit e-mailadres.', 'siw' ),
			], 200 );	
		}

		if ( true === $this->subscribe( $first_name, $email ) ) {
			return new WP_REST_Response( [
				'success' => true,
				'message' => __( 'Je bent er bijna! Check je inbox voor de bevestigingsmail om je aanmelding voor de nieuwsbrief te bevestigen.', 'siw' ),
			], 200 );
		}

		return new WP_REST_Response( [
			'success' => false,
			'message' => __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		], 200 );
	}

	/**
	 * Verwerk de aanmelding
	 *
	 * @param string $first_name
	 * @param string $email
	 * @return bool
	 * 
	 * @todo verplaatsen naar aparte klasse zodat Mailpoet makkelijk te vervangen is
	 */
	protected function subscribe( string $first_name, string $email ) {
		/* Meerdere aanmelding van zelfde IP-adres binnen X uur blokkeren*/
		$helperUser = WYSIJA::get( 'user', 'helper' );

		if ( ! $helperUser->throttleRepeatedSubscriptions() ) {
			return false;
		}

		$data_subscriber = [
			'user'      => [
				'firstname' => $first_name,
				'email'     => $email,
			],
			'user_list' => [ 'list_ids' => [ siw_get_option( 'newsletter_list' ) ] ],
		];
	
		$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber );
		if ( is_numeric( $user_id ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Geeft aan of het emailadres van een geblokkeerd domein komt
	 *
	 * @param string $email
	 * @return bool
	 */
	public function is_blocked_domain( string $email ) {
		//TODO: instelling van maken
		$blocked_domains = apply_filters( 'siw_blocked_domains_for_newsletter', ['siw.nl'] ); 
		$domain = substr( $email, strrpos( $email, '@' ) + 1 );

		return in_array( $domain, $blocked_domains );
	}

}
