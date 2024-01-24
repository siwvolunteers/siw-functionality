<?php declare(strict_types=1);

namespace SIW\Attributes;

use Attribute;

#[Attribute( Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE )]
class Add_Action {

	public function __construct( private string $tag, private int $priority = 10 ) {}

	public function add( callable $function_to_add, int $accepted_args = 1 ): void {
		add_action( $this->tag, $function_to_add, $this->priority, $accepted_args );
	}
}
