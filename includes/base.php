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
	final public static function init( object ...$args ): static {
		$self = new static( ...$args );
		$self->reflection_class = new \ReflectionClass( $self );
		$self->add_hooks();
		$self->add_shortcodes();
		return $self;
	}

	/** Voeg hooks toe */
	final protected function add_hooks(): void {

		$methods = $this->reflection_class->getMethods( \ReflectionMethod::IS_PUBLIC );
		foreach ( $methods as $method ) {
			$hook_attributes = array_merge(
				$method->getAttributes( \SIW\Attributes\Action::class ),
				$method->getAttributes( \SIW\Attributes\Filter::class ),
				$method->getAttributes( \SIW\Attributes\Add_Action::class ),
				$method->getAttributes( \SIW\Attributes\Add_Filter::class )
			);

			foreach ( $hook_attributes as $attribute ) {
				/** @var \SIW\Attributes\Add_Action|\SIW\Attributes\Add_Filter */
				$hook = $attribute->newInstance();
				$hook->add( [ $this, $method->getName() ], $method->getNumberOfParameters() );
			}
		}

		$constants = $this->reflection_class->getReflectionConstants();
		foreach ( $constants as $constant ) {
			$filter_attributes = array_merge(
				$method->getAttributes( \SIW\Attributes\Filter::class ),
				$method->getAttributes( \SIW\Attributes\Add_Filter::class )
			);
			foreach ( $filter_attributes as $attribute ) {
				/** @var \SIW\Attributes\Add_Filter */
				$hook = $attribute->newInstance();
				$hook->add( fn()=> $constant->getValue() );
			}
		}
	}

	/** Voegt shortcodes toe */
	final protected function add_shortcodes() {
		$methods = $this->reflection_class->getMethods( \ReflectionMethod::IS_PUBLIC );
		foreach ( $methods as $method ) {
			$shortcode_attributes = $method->getAttributes( \SIW\Attributes\Add_Shortcode::class );

			foreach ( $shortcode_attributes as $attribute ) {
				/** @var \SIW\Attributes\Add_Shortcode */
				$shortcode = $attribute->newInstance();
				$shortcode->add( [ $this, $method->getName() ] );
			}
		}
	}
}
