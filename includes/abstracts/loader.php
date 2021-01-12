<?php declare(strict_types=1);

namespace SIW\Abstracts;

/**
 * Basisklasse voor loader
 * 
 * @copyright 2020 SIW Internationale Vrijwilligersprojecten
 * @since     3.3.0
 */
abstract class Loader {

	/**
	 * ID van loader (voor filters)
	 */
	protected string $id;

	/**
	 * Classes
	 */
	protected array $classes = [];

	/**
	 * Namespace voor interface
	 */
	protected string $interface_namespace;

	/**
	 * Init
	 */
	public static function init() {
		$self = new static();

		$classes = array_map(
			fn( string $class ) : string => "\\SIW\\{$self->interface_namespace}\\{$class}",
			$self->classes
		);

		//Filter voor extensies
		$classes = apply_filters( "siw_{$self->id}_loader_classes", $classes );

		foreach ( $classes as $class ) {
			if ( class_exists( $class ) ) { //TODO: logging als class niet bestaat?
				$object = new $class;
				$self->load( $object );
			}
		}
	}

	/**
	 * Laadt 1 klasse
	 *
	 * @param object $class
	 */
	abstract protected function load( object $class );

	/**
	 * Controleer of Object een bepaalde interface implementeert
	 *
	 * @param Object $object
	 * @param string $interface
	 *
	 * @return bool
	 */
	protected function implements_interface( Object $object, string $interface ) : bool {
		return in_array( "SIW\\Interfaces\\{$this->interface_namespace}\\{$interface}", class_implements( $object ) );
	}

	/**
	 * Controleer of Object een bepaalde abstracte klasse extend
	 *
	 * @param Object $object
	 * @param string $abstract
	 *
	 * @return bool
	 */
	protected function extends_abstract( Object $object, string $abstract) : bool {
		return is_subclass_of( $object, "SIW\\Abstracts\\{$abstract}" );
	}

}
