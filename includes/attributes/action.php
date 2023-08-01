<?php declare(strict_types=1);

namespace SIW\Attributes;

use Attribute;

/**
 * Attribute om methode aan een action toe te voegen
 *
 * @copyright 2022 SIW Internationale Vrijwilligersprojecten
 * @deprecated gebruik Add_Action
 */
#[Attribute( Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE )]
class Action extends Add_Action {

}
