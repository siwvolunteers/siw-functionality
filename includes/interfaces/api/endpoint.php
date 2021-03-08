<?php declare(strict_types=1);

namespace SIW\Interfaces\API;

/**
 * Interface voor API endpoint
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
interface Endpoint {

	/** Geeft id van endpoint terug */
	public function get_id() : string;

	/** Geeft methodes terug (array van \WP_Http methods )*/
	public function get_methods() : array;

	/** Geeft args terug*/
	public function get_args() : array;

	/** Script data voor endpoint */
	public function get_script_data() : array;

	/** Callback voor endpoint */
	public function callback( \WP_REST_Request $request ) : \WP_REST_Response;
}
