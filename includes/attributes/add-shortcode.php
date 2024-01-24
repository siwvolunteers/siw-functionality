<?php declare(strict_types=1);

namespace SIW\Attributes;

use Attribute;

#[Attribute( Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION )]
class Add_Shortcode {

	public function __construct( private string $shortcode ) {}

	public function add( callable $function_to_add ): void {

		if ( ! str_starts_with( $this->shortcode, 'siw_' ) ) {
			$this->shortcode = 'siw_' . $this->shortcode;
		}

		add_shortcode( $this->shortcode, $function_to_add );
	}
}
