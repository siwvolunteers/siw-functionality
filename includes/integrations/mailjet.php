<?php declare(strict_types=1);

namespace SIW\Integrations;

use SIW\Config;
use SIW\Helpers\HTTP_Request;
use SIW\Helpers\Template;
use SIW\Integrations\Mailjet\Data_Type;
use SIW\Integrations\Mailjet\Property_Namespace;
use SIW\Integrations\Mailjet\Operation;

/**
 * Interface met Mailjet
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 *
 * @link      https://dev.mailjet.com/
 */
class Mailjet {

	/** API url */
	const API_URL = 'https://api.mailjet.com/v3/REST/';

	/** Protected constructor */
	final protected function __construct() {}

	/** Create */
	public static function create(): static {
		$self = new static();
		return $self;
	}

	/** Geeft API url terug */
	protected function get_api_url( Operation $route, array $args = [] ): ?string {
		$operation_path = self::API_URL . $route->value;
		return Template::create()->set_template( $operation_path )->set_context( $args )->parse_template();
	}

	/** Start HTTP request: */
	protected function create_http_request( string $url ): HTTP_Request {
		return HTTP_Request::create( $url )
			->set_basic_auth( Config::get_mailjet_api_key(), Config::get_mailjet_secret_key() );
	}

	/** Voegt abonnee toe aan maillijst */
	public function subscribe_user( string $email, int $list_id, array $properties = [] ): bool {

		// Hash van email/list combinatie
		$hash = siw_hash( $email . $list_id );

		if ( get_transient( "mailjet_subscribe_user_{$hash}" ) ) {
			return false;
		}

		$url = $this->get_api_url( Operation::SUBSCRIBE_USER_TO_LIST, [ 'list_id' => $list_id ] );
		$body = [
			'Email'      => $email,
			'Action'     => 'addnoforce',
			'Properties' => $properties,
		];

		$response = $this->create_http_request( $url )->post( $body );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		set_transient( "mailjet_subscribe_user_{$hash}", true, DAY_IN_SECONDS );
		return true;
	}

	/** Haalt Mailjet lijsten op */
	public function get_lists( array $args = [] ): array {

		$defaults = [
			'IsDeleted' => false,
			'Name'      => null,
		];

		$args = wp_parse_args( $args, $defaults );
		$args = array_filter( $args );

		$url = $this->get_api_url( Operation::RETRIEVE_LISTS, [] );
		$response = $this->create_http_request( $url )->add_query_args( $args )->get();
		if ( is_wp_error( $response ) ) {
			return [];
		}

		// data omzetten
		return array_map(
			function( $mailjet_list ) {
				return [
					'id'               => $mailjet_list['ID'],
					'name'             => $mailjet_list['Name'],
					'subscriber_count' => $mailjet_list['SubscriberCount'],
				];
			},
			$response['Data']
		);
	}

	/** Voegt lijst toe */
	public function create_list( string $name ): ?int {
		$url = $this->get_api_url( Operation::CREATE_LIST );
		$response = $this->create_http_request( $url )->post( [ 'Name' => $name ] );
		if ( is_wp_error( $response ) ) {
			return null;
		}
		return $response['Data'][0]['ID'] ?? null;
	}

	public function retrieve_properties( string $index = 'id' ): array {
		$url = $this->get_api_url( Operation::RETRIEVE_PROPERTIES );
		$response = $this->create_http_request( $url )->get();

		if ( is_wp_error( $response ) ) {
			return [];
		}

		// data omzetten
		return array_column(
			array_map(
				function( $property ) {
					return [
						'id'        => $property['ID'],
						'name'      => $property['Name'],
						'namespace' => $property['NameSpace'],
						'datatype'  => $property['Datatype'],
					];
				},
				$response['Data']
			),
			null,
			$index
		);
	}

	public function create_property( string $name, Data_Type $data_type, Property_Namespace $property_namespace = Property_Namespace::STATIC ): ?int {

		$url = $this->get_api_url( Operation::CREATE_PROPERTY );

		$body = [
			'Name'      => $name,
			'Datatype'  => $data_type->value,
			'NameSpace' => $property_namespace->value,
		];
		$response = $this->create_http_request( $url )->post( $body );
		if ( is_wp_error( $response ) ) {
			return null;
		}
		return $response['Data'][0]['ID'] ?? null;
	}

}
