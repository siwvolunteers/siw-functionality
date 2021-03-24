<?php declare(strict_types=1);

namespace SIW\External;

use SIW\Helpers\HTTP_Request;

/**
 * Interface met Mailjet
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * 
 * @link      https://dev.mailjet.com/
 */
class Mailjet {

	/** API url */
	const API_URL = 'https://api.mailjet.com';

	/** Api-versie */
	protected string $api_version = 'v3';

	/** API key */
	protected string $api_key;

	/** Secret key */
	protected string $secret_key;

	/** Zet API keys */
	public function __construct() {
		$this->api_key = siw_get_option( 'mailjet.api_key', '' );
		$this->secret_key = siw_get_option( 'mailjet.secret_key', '' );
	}
	
	/** Voegt abonnee toe aan maillijst */
	public function subscribe_user( string $email, $list_id, array $properties = [] ) : bool {

		$url = self::API_URL . "/{$this->api_version}/REST/contactslist/{$list_id}/managecontact";
		$body = json_encode( [
			'Email'      => $email,
			'Action'     => 'addnoforce',
			'Properties' => $properties
		]);

		$response = HTTP_Request::create( $url )
			->add_accepted_response_code( \WP_Http::CREATED )
			->set_basic_auth( $this->api_key, $this->secret_key )
			->post( $body );

		if ( is_wp_error( $response ) ) {
			return false;
		}
		//TODO: verdere check op response?
		return true;
	}

	/** Haalt maillijsten op */
	public function get_lists() : array {
		$lists = get_transient( 'siw_newsletter_lists' );
		if ( ! is_array( $lists ) ) {
			$lists = $this->retrieve_lists();
			if ( empty( $lists ) ) {
				return [];
			}
			set_transient( 'siw_newsletter_lists', $lists, HOUR_IN_SECONDS );
		}
		return $lists;
	}

	/** Haalt maillijsten op */
	protected function retrieve_lists() : array {

		$url = self::API_URL . "/{$this->api_version}/REST/contactslist";
		$response = HTTP_Request::create( $url )
			->set_basic_auth( $this->api_key, $this->secret_key )
			->get();
		if ( is_wp_error( $response ) ) {
			return [];
		}
		
		//Verwijderde lijst eruit filteren
		$lists = wp_list_filter( $response['Data'], [ 'IsDeleted' => false ] );

		//data omzetten
		return array_map(
			function( $list ) {
				return [
					'id'               => $list['ID'],
					'name'             => $list['Name'],
					'subscriber_count' => $list['SubscriberCount'],
				];
			},
			$lists
		);
	}

	/** Haalt gegevens van lijst op */
	public function get_list( string $list_id ) : array {
		$list = get_transient( "siw_newsletter_list_{$list_id}" );

		if ( ! is_array( $list ) ) {
			$list = $this->retrieve_list( $list_id );
			if ( empty( $list ) ) {
				return [];
			}
			set_transient( "siw_newsletter_list_{$list_id}", $list, HOUR_IN_SECONDS );
		}
		return $list;
	}

	/** Haalt gegevens van lijst op */
	protected function retrieve_list( string $list_id ) : array {
		$url = self::API_URL . "/{$this->api_version}/REST/contactslist/{$list_id}";

		$response = HTTP_Request::create( $url )
			->set_basic_auth( $this->api_key, $this->secret_key )
			->get();
		if ( is_wp_error( $response ) ) {
			return [];
		}

		$list = $response['Data'][0];
		return [
			'id'               => $list['ID'],
			'name'             => $list['Name'],
			'subscriber_count' => $list['SubscriberCount'],
		];
	}
}
