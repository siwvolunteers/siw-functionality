<?php declare(strict_types=1);

namespace SIW\Data;

/**
 * Basistype voor Data Object
 * 
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */
abstract class Data {

	/** Init */
	public function __construct( array $data ) {
		$class_vars = get_class_vars( static::class );
		
		$data = wp_parse_args( $data, $class_vars );
		$data = wp_array_slice_assoc( $data, array_keys( $class_vars ) );
		
		foreach( $data as $key => $value ) {
			$this->$key = $value;
		}
	}
}