<?php declare(strict_types=1);

namespace SIW\External;

use PhpParser\Builder\FunctionTest;

/**
 * Interface met Mailjet
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * @since     3.0.0
 * 
 * @link      https://dev.mailjet.com/
 * 
 * @todo      Request class gebruiken
 */
class Mailjet {

	/**
	 * API url
	 *
	 * @var string
	 */
	const API_URL = 'https://api.mailjet.com';

	/**
	 * Api-versie
	 *
	 * @var string
	 */
	protected $api_version = 'v3';

	/**
	 * API key
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * Secret key
	 *
	 * @var string
	 */
	protected $secret_key;

	/**
	 * Zet API keys
	 */
	public function __construct() {
		$this->api_key = siw_get_option( 'mailjet_api_key' );
		$this->secret_key = siw_get_option( 'mailjet_secret_key' );
	}
	
	/**
	 * Voegt abonnee toe aan maillijst
	 *
	 * @param string $email
	 * @param int $list_id
	 * @param array $properties
	 *
	 * @return bool
	 */
	public function subscribe_user( string $email, $list_id, array $properties = [] ) {

		$url = self::API_URL . "/{$this->api_version}/REST/contactslist/{$list_id}/managecontact";
		$body = json_encode( [
			'Email'      => $email,
			'Action'     => 'addnoforce',
			'Properties' => $properties
		]);


		$args = [
			'timeout'     => 60,
			'redirection' => 0,
			'headers'     => [ 
				'Authorization' => 'Basic ' . base64_encode("{$this->api_key}:{$this->secret_key}"),
				'accept'       => 'application/json',
				'content-type' => 'application/json'
			],
			'body'        => $body,
		];
		$response = wp_safe_remote_post( $url, $args );
		if ( false == $this->check_response( $response ) ) {
			return false;
		}

		//TODO: verdere check op response
		return true;
	}

	/**
	 * Haalt maillijsten op
	 *
	 * @return array
	 */
	public function get_lists() {
		$lists = get_transient( 'siw_newsletter_lists' );

		if ( false === $lists ) {
			$lists = $this->retrieve_lists();
			if ( false == $lists ) {
				return [];
			}
			set_transient( 'siw_newsletter_lists', $lists, HOUR_IN_SECONDS );
		}
		return $lists;
	}

	/**
	 * Haalt maillijsten op
	 *
	 * @return array
	 */
	protected function retrieve_lists() {

		$url = self::API_URL . "/{$this->api_version}/REST/contactslist";
		$args = [
			'timeout'     => 60,
			'redirection' => 0,
			'headers'     => [ 
				'Authorization' =>'Basic ' . base64_encode("{$this->api_key}:{$this->secret_key}"),
				'accept'       => 'application/json',
				'content-type' => 'application/json'
			],
		];

		$response = wp_safe_remote_get( $url, $args );

		if ( false == $this->check_response( $response ) ) {
			return [];
		}
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		$lists = [];
		foreach ( $body['Data'] as $list ) {
			if ( $list['IsDeleted'] ) {
				continue;
			}
			$lists[] = [
				'id'               => $list['ID'],
				'name'             => $list['Name'],
				'subscriber_count' => $list['SubscriberCount'],
			];
		}
		return $lists;
	}

	/**
	 * Haalt gegevens van lijst op
	 *
	 * @param int $list_id
	 *
	 * @return array
	 */
	public function get_list( int $list_id ) {
		$list = get_transient( "siw_newsletter_list_{$list_id}" );

		if ( false === $list ) {
			$list = $this->retrieve_list( $list_id );
			if ( false == $list ) {
				return [];
			}
			set_transient( "siw_newsletter_list_{$list_id}", $list, HOUR_IN_SECONDS );
		}
		return $list;
	}

	/**
	 * Haalt gegevens van lijst op
	 *
	 * @param int $list_id
	 *
	 * @return array
	 */
	protected function retrieve_list( int $list_id ) {
		$url = self::API_URL . "/{$this->api_version}/REST/contactslist/{$list_id}";
		$args = [
			'timeout'     => 60,
			'redirection' => 0,
			'headers'     => [ 
				'Authorization' =>'Basic ' . base64_encode("{$this->api_key}:{$this->secret_key}"),
				'accept'       => 'application/json',
				'content-type' => 'application/json'
			],
		];

		$response = wp_safe_remote_get( $url, $args );

		if ( false == $this->check_response( $response ) ) {
			return [];
		}
		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		$list = $body['Data'][0];
		return [
			'id'               => $list['ID'],
			'name'             => $list['Name'],
			'subscriber_count' => $list['SubscriberCount'],
		];
	}

	/**
	 * Controleert response
	 *
	 * @param array|\WP_Error $response
	 * @return bool
	 */
	protected function check_response( $response ) {
		if ( is_wp_error( $response ) ) {
			return false;
		}
	
		$statuscode = wp_remote_retrieve_response_code( $response );
		if ( \WP_Http::OK != $statuscode && \WP_Http::CREATED != $statuscode ) {
			return false;
		}
		return true;
	}

}
