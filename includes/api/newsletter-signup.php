<?php declare(strict_types=1);

namespace SIW\API;

use SIW\Interfaces\API\Endpoint as Endpoint_Interface;

use SIW\Helpers\Spam_Check as Spam_Check_Helper;
use SIW\Newsletter\Confirmation_Email;

/**
 * API endpoint voor aanmelding nieuwsbrief
 *
 * @copyright 2019-2021 SIW Internationale Vrijwilligersprojecten
 */
class Newsletter_Signup implements Endpoint_Interface {

	/** {@inheritDoc} */
	public function get_id(): string {
		return 'newsletter_signup';
	}

	/** {@inheritDoc} */
	public function get_methods(): array {
		return [ \WP_REST_Server::CREATABLE];
	}

	/** {@inheritDoc} */
	public function get_args(): array {
		return [
			'name' => [
				'required' => true,
				'type'     => 'string',
			],
			'email' => [
				'required' => true,
				'type'     => 'string',
				'format'   => 'email'
			]
		];
	}

	/** {@inheritDoc} */
	public function get_script_data(): array {
		return [];
	}
	
	/** {@inheritDoc} */
	public function callback( \WP_REST_Request $request) : \WP_REST_Response {

		$first_name = $request->get_param( 'name' );
		$email = $request->get_param( 'email' );

		if ( $this->is_blocked_domain( $email ) ) {
			return new \WP_REST_Response( [
				'message' => __( 'Het is niet mogelijk om je aan te melden met dit e-mailadres.', 'siw' ),
			], \WP_Http::BAD_REQUEST );
		}
		
		//Spam check
		$spam_check = Spam_Check_Helper::create()
			->set_email( $email );
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ){
			$spam_check->set_ip( $_SERVER['REMOTE_ADDR']);
		}
	
		if ( $spam_check->is_spam() ) {
			return new \WP_REST_Response( [
				'message' => __( 'Het is niet mogelijk om je aan te melden met dit e-mailadres.', 'siw' ),
			], \WP_Http::BAD_REQUEST );
		}

		if ( true === $this->subscribe( $first_name, $email ) ) {
			return new \WP_REST_Response( [
				'message' => __( 'Je bent er bijna! Check je inbox voor de bevestigingsmail om je aanmelding voor de nieuwsbrief te bevestigen.', 'siw' ),
			], \WP_Http::CREATED );
		}

		return new \WP_REST_Response( [
			'message' => __( 'Er is helaas iets misgegaan. Probeer het later nog eens.', 'siw' ),
		], \WP_Http::OK );
	}

	/** Verwerk de aanmelding TODO: direct newsletter klasse gebruiken?*/
	protected function subscribe( string $first_name, string $email ) {
		return Confirmation_Email::create(
			$email,
			(int) siw_get_option( 'newsletter_list' ),
			[
				'firstname' => $first_name
			]
		)->send();
	}

	/** Geeft aan of het emailadres van een geblokkeerd domein komt TODO: siw.nl toevoegen op productie */
	public function is_blocked_domain( string $email ) {
		$blocked_domains = siw_get_option( 'blocked_domains', [] );
		$domain = substr( $email, strrpos( $email, '@' ) + 1 );

		return in_array( $domain, $blocked_domains );
	}
}
