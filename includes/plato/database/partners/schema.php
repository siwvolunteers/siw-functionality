<?php declare(strict_types=1);

namespace SIW\Plato\Database\Partners;

class Schema extends \SIW\Plato\Database\Schema {

	public $columns = [
		[
			'name'     => 'id',
			'type'     => 'BIGINT',
			'length'   => 20,
			'extra'    => 'AUTO_INCREMENT',
			'unsigned' => true,
			'primary'  => true,
			'sortable' => true,
		],
		[
			'name'       => 'technical_key',
			'type'       => 'CHAR',
			'length'     => 36,
			'searchable' => true,
			'cache_key'  => true,
		],
		[
			'name'       => 'name',
			'type'       => 'mediumtext',
			'searchable' => true,
			'sortable'   => true,
		],
	];
}
