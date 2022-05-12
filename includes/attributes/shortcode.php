<?php declare(strict_types=1);

namespace SIW\Attributes;

use Attribute;

/**
 * Atrribute om methode aan een shortcode toe te voegen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 */
#[Attribute( Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION )]
class Shortcode {

	/** Constructor */
	public function __construct( private string $shortcode ) {}

	/** Voegt filter toe */
	public function add( callable $function_to_add ): void {
		add_shortcode( $this->shortcode, $function_to_add );
	}

}
