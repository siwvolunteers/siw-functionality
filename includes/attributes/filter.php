<?php declare(strict_types=1);

namespace SIW\Attributes;

use Attribute;

/**
 * Attribute om methode aan een filter toe te voegen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * @deprecated gebruik Add_Filter
 */
#[Attribute( Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE )]
class Filter extends Add_Filter {

}
