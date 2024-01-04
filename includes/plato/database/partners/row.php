<?php declare(strict_types=1);

namespace SIW\Plato\Database\Partners;

class Row extends \BerlinDB\Database\Row {

	readonly public int $id;
	readonly public string $technical_key;
	readonly public string $name;

	public function __construct( $item ) {
		$item->id = (int) $item->id;
		$item->technical_key = (string) $item->technical_key;
		$item->name = (string) $item->name;

		parent::__construct( $item );
	}
}
