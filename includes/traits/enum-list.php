<?php declare(strict_types=1);

namespace SIW\Traits;

use SIW\Interfaces\Enums\Labels;

trait Enum_List {

	public static function list(): array {
		return array_map(
			fn( Labels $e ) => $e->label(),
			array_column( self::cases(), null, 'value' )
		);
	}
}
