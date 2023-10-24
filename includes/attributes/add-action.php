<?php declare(strict_types=1);

namespace SIW\Attributes;

use Attribute;

/**
 * Attribute om methode aan een action toe te voegen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
#[Attribute( Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE )]
class Add_Action {

	/** Constructor */
	public function __construct( private string $tag, private int $priority = 10 ) {}

	/** Voegt filter toe */
	public function add( callable $function_to_add, int $accepted_args = 1 ): void {
		add_action( $this->tag, $function_to_add, $this->priority, $accepted_args );
	}
}
