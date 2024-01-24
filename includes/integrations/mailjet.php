<?php declare(strict_types=1);

namespace SIW\Integrations;

use SIW\Config;
use SIW\Data\Mailjet\Data_Type;
use SIW\Data\Mailjet\Operation;
use SIW\Data\Mailjet\Property_Namespace;
use SIW\Helpers\HTTP_Request;
use SIW\Helpers\Template;

/**
 * @link      https://dev.mailjet.com/
 */
class Mailjet {

	private const API_URL = 'https://api.mailjet.com/v3/REST/';

	final protected function __construct() {}

	public static function create(): static {
		$self = new static();
		return $self;
	}

	protected function get_api_url( Operation $operation, array $args = [] ): ?string {
		$operation_path = self::API_URL . $operation->value;

		return Template::create()->set_template( $operation_path )->set_context( $args )->parse_template();
	}

	protected function create_http_request( string $url ): HTTP_Request {
		return HTTP_Request::create( $url )
			->set_basic_auth( Config::get_mailjet_api_key(), Config::get_mailjet_secret_key() );
	}

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

	public function get_lists( array $args = [] ): array {

		$defaults = [
			'IsDeleted' => false,
			'Name'      => null,
		];

		$args = wp_parse_args( $args, $defaults );
		$args = array_filter( $args );

		$url = $this->get_api_url( Operation::MANAGE_LISTS, [] );
		$response = $this->create_http_request( $url )->add_query_args( $args )->get();
		if ( is_wp_error( $response ) ) {
			return [];
		}
		return array_map(
			function ( $mail_list ): array {
				return [
					'id'               => $mail_list['ID'],
					'name'             => $mail_list['Name'],
					'subscriber_count' => $mail_list['SubscriberCount'],
				];
			},
			$response['Data']
		);
	}

	public function create_list( string $name ): ?int {
		$url = $this->get_api_url( Operation::MANAGE_LISTS );
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

		return array_column(
			array_map(
				function ( $property ) {
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

	public function create_property( string $name, Data_Type $datatype, Property_Namespace $mailjet_namespace = Property_Namespace::STATIC ): ?int {
		$url = $this->get_api_url( Operation::CREATE_PROPERTY );

		$body = [
			'Name'      => $name,
			'Datatype'  => $datatype->value,
			'NameSpace' => $mailjet_namespace->value,
		];
		$response = $this->create_http_request( $url )->post( $body );
		if ( is_wp_error( $response ) ) {
			return null;
		}
		return $response['Data'][0]['ID'] ?? null;
	}
}
