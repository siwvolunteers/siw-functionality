<?php declare(strict_types=1);

namespace SIW\External;

use Pharaonic\DotArray\DotArray;
use SIW\Helpers\HTTP_Request;
use SIW\Util\Logger;

/**
 * Google Maps
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 * 
 * @link      https://developers.google.com/maps/documentation
 */
class Google_Maps {

	/** API url */
	const API_URL = 'https://maps.googleapis.com/maps/api/';

	/** Endpoint van geocoding TODO: php8.1 enum van alle api endpoints */
	const GEOCODING_API_ENDPOINT = 'geocode';
	
	/** Status code OK TODO:php8.1 enum van statuscodes maken */
	const STATUS_OK = 'OK';

	/** Toegestane result types: TODO: php8.1 enum van maken */
	const RESULT_TYPES = [
		'street_address',
		'route',
		'intersection',
		'political',
		'country',
		'administrative_area_level_1',
		'administrative_area_level_2',
		'administrative_area_level_3',
		'administrative_area_level_4',
		'administrative_area_level_5',
		'colloquial_area',
		'locality',
		'sublocality',
		'neighborhood',
		'premise',
		'subpremise',
		'plus_code',
		'postal_code',
		'natural_feature',
		'airport',
		'park',
		'point_of_interest',
	];

	/** Toegestane location types: TODO: php8.1 enum van maken */
	const LOCATION_TYPES= [ 
		'ROOFTOP',
		'RANGE_INTERPOLATED',
		'GEOMETRIC_CENTER',
		'APPROXIMATE',
	];

	/** API key */
	protected string $api_key;

	/** Init */
	public function __construct() {
		$this->api_key = siw_get_option( 'google_maps.server_side_api_key', '' );
	}

	/** Reverse geocoding (van adres naar coördinaten)  */
	public function reverse_geocode( string $address ): ?array {
		$url = add_query_arg( [
			'key'      => $this->api_key,
			'address'  => urlencode( $address )
		], $this->get_api_endpoint_url( self::GEOCODING_API_ENDPOINT ) );

		$response = HTTP_Request::create( $url )->get();

		if ( is_wp_error( $response ) || ! $this->check_status( $response ) ) {
			return null;
		}

		$results = new DotArray( $response['results'][0] );

		return [
			'latitude'  => $results->get('geometry.location.lat'),
			'longitude' => $results->get('geometry.location.lng'),
		];
	}

	/** Geocoding (van coördinaten naar locatie) */
	public function geocode( float $latitude, float $longitude, string $location_type = null, string $result_type = null ): ?array {
		$url = add_query_arg( [
			'key'           => $this->api_key,
			'latlng'        => "{$latitude},{$longitude}",
			'location_type' => ( $location_type != null && $this->is_valid_location_type( $location_type ) ) ? $location_type : null,
			'result_type'   => ( $result_type != null && $this->is_valid_result_type( $result_type ) ) ? $result_type : null,
		], $this->get_api_endpoint_url( self::GEOCODING_API_ENDPOINT ) );
		$response = HTTP_Request::create( $url )->get();

		if ( is_wp_error( $response ) || ! $this->check_status( $response ) ) {
			return null;
		}

		$address_components = new DotArray( $response['results'][0]['address_components'] );

		//TODO: meerder resultaten teruggeven + formattering
		$address = [];
		for ( $i=0; $i < $address_components->count(); $i++ ) {
			$address[ $address_components->get( "{$i}.types.0" )] = $address_components->get( "{$i}.long_name" );
		}
		return $address;
	}

	/** Check of status ok is; log anders de foutmelding */
	protected function check_status( array $response ): bool {
		if ( $response['status'] == self::STATUS_OK ) {
			return true;
		}
		Logger::error( $response['error_message'], 'siw-google-maps' );
		return false;
	}

	/** Check of de location typ valide is */
	protected function is_valid_location_type( string $location_type ): bool {
		return in_array( $location_type, self::LOCATION_TYPES );
	}

	/** Check of het result type valide is */
	protected function is_valid_result_type( string $result_type ): bool {
		return in_array( $result_type, self::RESULT_TYPES );
	}

	/** Geeft api endpoint url terug */
	protected function get_api_endpoint_url( string $endpoint ): string {
		return self::API_URL . $endpoint . '/json';
	}
}
