<?php declare(strict_types=1);

namespace SIW;

/**
 * Basisklasse voor classe met hooks
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
abstract class Base {

	/** Reflection class */
	protected \ReflectionClass $reflection_class;

	/** Constructor (te gebruiken voor constructor property promotion */
	protected function __construct() {}

	/** New */
	public static function init( object ...$args ): static {
		$self = new static( ...$args );
		$self->reflection_class = new \ReflectionClass( $self );
		$self->add_hooks();
		return $self;
	}

	/** Voeg hooks toe */
	public function add_hooks(): void {

		$methods = $this->reflection_class->getMethods( \ReflectionMethod::IS_PUBLIC );
		foreach ( $methods as $method ) {
			$hook_attributes = array_merge(
				$method->getAttributes( \SIW\Attributes\Action::class ),
				$method->getAttributes( \SIW\Attributes\Filter::class )
			);

			foreach ( $hook_attributes as $attribute ) {
				/** @var \SIW\Attributes\Action|\SIW\Attributes\Filter */
				$hook = $attribute->newInstance();
				$hook->add( [ $this, $method->getName() ], $method->getNumberOfParameters() );
			}
		}

		$constants = $this->reflection_class->getReflectionConstants();
		foreach ( $constants as $constant ) {
			$filter_attributes = $constant->getAttributes( \SIW\Attributes\Filter::class );
			foreach ( $filter_attributes as $attribute ) {
				/** @var \SIW\Attributes\Filter */
				$hook = $attribute->newInstance();
				$hook->add( fn()=> $constant->getValue() );
			}
		}
	}
}
