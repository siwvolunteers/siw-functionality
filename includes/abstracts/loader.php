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
	protected array $classes;

	/**
	 * Namespace voor interface
	 */
	protected string $interface_namespace;

	/**
	 * Init
	 */
	public static function init() {
		$self = new static();

		$classes = apply_filters( "siw_{$self->id}_loader_classes", $self->classes );

		foreach ( $classes as $class ) {
			$self->load( $class );
		}
	}

	/**
	 * Laadt 1 klasse
	 *
	 * @param string $class
	 */
	abstract protected function load( string $class );

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

}
