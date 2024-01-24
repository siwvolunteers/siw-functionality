<?php declare(strict_types=1);

namespace SIW\Plato\Database\Free_Places;

class Table extends \SIW\Plato\Database\Table {

	public $name = 'plato_free_places';

	protected $db_version_key = 'plato_free_places_version';

	public $description = 'Plato FPL';

	protected $version = '1.0.0';

	protected $upgrades = [];

	public function get_schema(): \SIW\Plato\Database\Schema {
		return new Schema();
	}

	public function get_query(): \BerlinDB\Database\Query {
		return new Query();
	}

	public function get_view_columns(): array {
		return [
			'project_id',
			'code',
			'start_date',
			'end_date',
			'numvol',
			'no_more_from',
		];
	}
}
