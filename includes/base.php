<?php declare(strict_types=1);

namespace SIW;

abstract class Base {

	protected \ReflectionClass $reflection_class;

	protected function __construct() {}

	final public static function init( object ...$args ): ?static {
		if ( ! static::should_load() ) {
			return null;
		}

		$self = new static( ...$args );
		$self->reflection_class = new \ReflectionClass( $self );
		$self->add_hooks();
		$self->add_shortcodes();
		return $self;
	}

	protected static function should_load(): bool {
		return true;
	}

	final protected function add_hooks(): void {

		$methods = $this->reflection_class->getMethods( \ReflectionMethod::IS_PUBLIC );
		foreach ( $methods as $method ) {
			$hook_attributes = array_merge(
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
				$constant->getAttributes( \SIW\Attributes\Add_Filter::class )
			);
			foreach ( $filter_attributes as $attribute ) {
				/** @var \SIW\Attributes\Add_Filter */
				$hook = $attribute->newInstance();
				$hook->add( fn()=> $constant->getValue() );
			}
		}
	}

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
