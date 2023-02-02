<?php declare(strict_types=1);

namespace SIW\Integrations;

use SIW\Config;
use SIW\Helpers\HTTP_Request;
use SIW\Helpers\Template;

/**
 * Interface met Mailjet
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 *
 * @link      https://dev.mailjet.com/
 */
class Mailjet {

	// Namespaces TODO: enum van maken
	const NAMESPACE_STATIC = 'static';
	const NAMESPACE_HISTORIC = 'historic';

	const NAMESPACES = [
		self::NAMESPACE_STATIC,
		self::NAMESPACE_HISTORIC,
	];

	// Data types TODO: enum van maken
	const DATA_TYPE_STRING = 'str';
	const DATA_TYPE_INTEGER = 'int';
	const DATA_TYPE_FLOAT = 'float';
	const DATA_TYPE_BOOLEAN = 'bool';
	const DATA_TYPE_DATETIME = 'datetime';

	const DATA_TYPES = [
		self::DATA_TYPE_STRING,
		self::DATA_TYPE_INTEGER,
		self::DATA_TYPE_FLOAT,
		self::DATA_TYPE_BOOLEAN,
		self::DATA_TYPE_DATETIME,
	];

	// Properies TODO: enum van maken
	const PROPERTY_FIRST_NAME = 'firstname';
	const PROPERTY_LAST_NAME = 'lastname';
	const PROPERTY_INTEREST_PROJECT_TYPE = 'interest_project_type';
	const PROPERTY_INTEREST_DESTINATION = 'interest_destination';
	const PROPERTY_REFERRAL = 'referral';
	const PROPERTY_AGE_RANGE = 'age_range';

	const PROPERTIES = [
		self::PROPERTY_FIRST_NAME            => self::DATA_TYPE_STRING,
		self::PROPERTY_LAST_NAME             => self::DATA_TYPE_STRING,
		self::PROPERTY_INTEREST_PROJECT_TYPE => self::DATA_TYPE_STRING,
		self::PROPERTY_INTEREST_DESTINATION  => self::DATA_TYPE_STRING,
		self::PROPERTY_REFERRAL              => self::DATA_TYPE_STRING,
		self::PROPERTY_AGE_RANGE             => self::DATA_TYPE_STRING,
	];


	// Operations + resources TODO: enum van maken
	const OPERATION_SUBSCRIBE_USER_TO_LIST = 'subscribe_user_to_list';
	const OPERATION_RETRIEVE_LISTS = 'retrieve_lists';
	const OPERATION_CREATE_LIST = 'create_list';
	const OPERATION_CREATE_PROPERTY = 'create_property';
	const OPERATION_RETRIEVE_PROPERTIES = 'retrieve_properties';

	const RESOURCES = [
		self::OPERATION_SUBSCRIBE_USER_TO_LIST => 'contactslist/{{ list_id }}/managecontact',
		self::OPERATION_RETRIEVE_LISTS         => 'contactslist',
		self::OPERATION_CREATE_LIST            => 'contactslist',
		self::OPERATION_CREATE_PROPERTY        => 'contactmetadata',
		self::OPERATION_RETRIEVE_PROPERTIES    => 'contactmetadata',
	];

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
	protected function get_api_url( string $operation, array $args = [] ): ?string {

		$operation_path = self::RESOURCES[ $operation ] ?? null;
		if ( null === $operation_path ) {
			return false;
		}

		$operation_path = self::API_URL . $operation_path;

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

		$url = $this->get_api_url( self::OPERATION_SUBSCRIBE_USER_TO_LIST, [ 'list_id' => $list_id ] );
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

		$url = $this->get_api_url( self::OPERATION_RETRIEVE_LISTS, [] );
		$response = $this->create_http_request( $url )->add_query_args( $args )->get();
		if ( is_wp_error( $response ) ) {
			return [];
		}

		// data omzetten
		return array_map(
			function( $list ) {
				return [
					'id'               => $list['ID'],
					'name'             => $list['Name'],
					'subscriber_count' => $list['SubscriberCount'],
				];
			},
			$response['Data']
		);
	}

	/** Voegt lijst toe */
	public function create_list( string $name ): ?int {
		$url = $this->get_api_url( self::OPERATION_CREATE_LIST );
		$response = $this->create_http_request( $url )->post( [ 'Name' => $name ] );
		if ( is_wp_error( $response ) ) {
			return null;
		}
		return $response['Data'][0]['ID'] ?? null;
	}


	public function retrieve_properties( string $index = 'id' ): array {
		$url = $this->get_api_url( self::OPERATION_RETRIEVE_PROPERTIES );
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

	public function create_property( string $name, string $datatype, string $namespace = 'static' ): ?int {

		$datatype = in_array( $datatype, self::DATA_TYPES, true ) ? $datatype : self::DATA_TYPE_STRING;

		$namespaces = [
			'static',
			'historic',
		];
		$namespace = in_array( $namespace, $namespaces, true ) ? $namespace : 'static';

		$url = $this->get_api_url( self::OPERATION_CREATE_PROPERTY );

		$body = [
			'Name'      => $name,
			'Datatype'  => $datatype,
			'NameSpace' => $namespace,
		];
		$response = $this->create_http_request( $url )->post( $body );
		if ( is_wp_error( $response ) ) {
			return null;
		}
		return $response['Data'][0]['ID'] ?? null;
	}

}
