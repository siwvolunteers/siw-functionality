<?php declare(strict_types=1);

namespace SIW\Plato\Database\Partners;

class Schema extends \BerlinDB\Database\Schema {

	public $columns = [
		[
			'name'     => 'id',
			'type'     => 'bigint',
			'length'   => 20,
			'unsigned' => true,
			'extra'    => 'auto_increment',
			'primary'  => true,
			'sortable' => true,
		],
		[
			'name'       => 'technical_key',
			'type'       => 'CHAR',
			'length'     => 32,
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
