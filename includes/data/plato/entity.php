<?php declare(strict_types=1);

namespace SIW\Data\Plato;

use BackedEnum;

trait Entity {

	abstract public function to_entity(): \BackedEnum&\SIW\Interfaces\Enums\Labels;

	public function label(): string {
		return $this->to_entity()->label();
	}

	public function slug(): string {
		return $this->to_entity()->value;
	}

	public static function list(): array {
		$list = array_combine(
			array_map( fn( BackedEnum $e ): string => $e->value, self::cases() ),
			array_map( fn( self $e ): string => $e->to_entity()->label(), self::cases() )
		);
		asort( $list );
		return $list;
	}
}
