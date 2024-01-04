<?php declare(strict_types=1);

namespace SIW\Plato\Database\Free_Places;

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
			'name'       => 'project_id',
			'type'       => 'CHAR',
			'length'     => 32,
			'searchable' => true,
			'sortable'   => true,
		],
		[
			'name'       => 'code',
			'type'       => 'VARCHAR',
			'length'     => 255,
			'searchable' => true,
			'sortable'   => true,
		],
		[
			'name'       => 'start_date',
			'type'       => 'DATE',
			'searchable' => true,
			'sortable'   => true,
		],
		[
			'name'       => 'end_date',
			'type'       => 'DATE',
			'searchable' => true,
			'sortable'   => true,
		],
		[
			'name'     => 'numvol',
			'type'     => 'TINYINT',
			'sortable' => true,
		],
		[
			'name'     => 'free_m',
			'type'     => 'TINYINT',
			'sortable' => true,
		],
		[
			'name'     => 'free_f',
			'type'     => 'TINYINT',
			'sortable' => true,
		],
		[
			'name'     => 'free_teen',
			'type'     => 'TINYINT',
			'sortable' => true,
		],
		[
			'name'     => 'reserved',
			'type'     => 'TINYINT',
			'sortable' => true,
		],
		[
			'name'       => 'no_more_from',
			'type'       => 'VARCHAR',
			'length'     => 255,
			'nullable'   => true,
			'searchable' => true,
		],
		[
			'name'       => 'remarks',
			'type'       => 'TEXT',
			'searchable' => true,
		],
		[
			'name'     => 'last_update',
			'type'     => 'DATE',
			'sortable' => true,
		],
		[
			'name'         => 'file_identifier_infosheet',
			'type'         => 'CHAR',
			'length'       => 32,
			'nullable'     => true,
			'xml_property' => 'url_infosheet',
		],
	];
}
