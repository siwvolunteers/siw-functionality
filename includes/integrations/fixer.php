<?php declare(strict_types=1);

namespace SIW\Integrations;

use SIW\Config;
use SIW\Helpers\HTTP_Request;

/**
 * Ophalen wisselkoersen bij fixer.io
 *
 * @copyright 2019-2023 SIW Internationale Vrijwilligersprojecten
 *
 * @link      https://fixer.io/documentation
 */
class Fixer {

	const API_URL = 'http://data.fixer.io/api/latest';

	protected string $transient_name = 'siw_exchange_rates';

	public static function create(): static {
		$self = new static();
		return $self;
	}

	public function get_rate( string $iso_code ): ?float {
		$exchange_rates = $this->get_rates();
		return $exchange_rates[ $iso_code ] ?? null;
	}

	public function get_rates(): array {
		$exchange_rates = get_transient( $this->transient_name );
		if ( ! is_array( $exchange_rates ) ) {
			$exchange_rates = $this->retrieve_rates();
			if ( is_null( $exchange_rates ) ) {
				return [];
			}
			set_transient( $this->transient_name, $exchange_rates, DAY_IN_SECONDS );
		}
		return $exchange_rates;
	}

	protected function retrieve_rates(): ?array {
		$url = add_query_arg(
			[
				'access_key' => Config::get_fixer_io_api_key(),
			],
			self::API_URL
		);

		$response = HTTP_Request::create( $url )->get();

		if ( is_wp_error( $response ) || false === $response['success'] ) {
			return null;
		}

		return array_map( fn( float $rate ) : float => 1 / $rate, $response['rates'] );
	}
}

