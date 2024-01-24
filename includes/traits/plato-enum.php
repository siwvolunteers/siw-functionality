<?php declare(strict_types=1);

namespace SIW\Traits;

use BackedEnum;
use SIW\Interfaces\Enums\Labels;
use SIW\Interfaces\Enums\Plato_Code;

trait Plato_Enum {
	public static function try_from_plato_code( string|int $plato_code ): ?\BackedEnum {
		$list = array_combine(
			array_map( fn( Plato_Code $e ): string => $e->plato_code(), self::cases() ),
			self::cases()
		);
		return $list[ $plato_code ] ?? null;
	}

	public static function plato_list(): array {
		$list = array_combine(
			array_map( fn( Plato_Code $e ): string => $e->plato_code(), self::cases() ),
			array_map( fn( Labels $e ): string => $e->label(), self::cases() )
		);

		asort( $list );
		return $list;
	}
}
