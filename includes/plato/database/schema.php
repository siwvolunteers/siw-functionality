<?php declare(strict_types=1);

namespace SIW\Plato\Database;

use BerlinDB\Database\Column;

class Schema extends \BerlinDB\Database\Schema {

	/** @return Column[]  */
	public function get_columns(): array {
		return $this->columns;
	}
}
