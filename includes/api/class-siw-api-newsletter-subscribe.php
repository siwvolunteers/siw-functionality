<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
	protected $callback = 'subscribe';

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
	public function validate_name( $param, $request, $key ) {
		return is_string( $param );
	}

	/**
	 * Undocumented function
	 *
	 * @param mixed $param
	 * @param WP_REST_Request $request
	 * @param string $key
	 * @return string
	 */
	public function sanitize_name( $param, $request, $key ) {
		return sanitize_text_field( $param );
	}

	/**
	 * Valideert e-mail
	 *
	 * @param mixed $param
	 * @param  WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function validate_email( $param, $request, $key ) {
		return is_email( $param );
	}

	/**
	 * 
	 *
	 * @param mixed $param
	 * @param  WP_REST_Request $request
	 * @param string $key
	 * @return bool
	 */
	public function sanitize_email( $param, $request, $key ) {
		return sanitize_email( $param );
	}

	/**
	 * Undocumented function
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 * 
	 * @todo splitsen
	 */
	public function subscribe( $request ) {

		$first_name = $request->get_param( 'name' );
		$email = $request->get_param( 'email' );

		$blocked_domains = apply_filters( 'siw_blocked_domains_for_newsletter', ['siw.nl'] );
		$domain = substr( $email, strrpos( $email, '@' ) + 1 );

		if ( in_array( $domain, $blocked_domains ) ) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => sprintf( __( 'Het is niet mogelijk om je aan te melden met een @%s adres.', 'siw' ), esc_html( $domain ) ),
			], 200 );
		}

		/* Meerdere aanmelding van zelfde IP-adres binnen X uur blokkeren*/
		$helperUser = WYSIJA::get( 'user', 'helper' );
		if( ! $helperUser->throttleRepeatedSubscriptions() ) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
			], 200 );
		}

		$data_subscriber = [
			'user'      => [
				'firstname' => $first_name,
				'email'     => $email,
			],
			'user_list' => [ 'list_ids' => [ siw_get_setting( 'newsletter_list' ) ] ],
		];
	
		$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber );
		if ( is_numeric( $user_id ) ) {
			return new WP_REST_Response( [
				'success' => true,
				'message' => __( 'Je bent er bijna! Check je inbox voor de bevestigingsmail om je aanmelding voor de nieuwsbrief te bevestigen.', 'siw' ),
			], 200 ); 
		}
		elseif ( $user_id ) {
			return new WP_REST_Response( [
				'success' => true,
				'message' => __( 'Je bent al ingeschreven.', 'siw' ),
			], 200 ); 
		}
		else {
			return new WP_REST_Response( [
				'success' => false,
				'message' => __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
			], 200 ); 
		}
	}
}