<?php declare(strict_types=1);

namespace SIW\Plato\Database\Partners;

class Table extends \SIW\Plato\Database\Table {

	public $name = 'plato_partners';

	protected $db_version_key = 'plato_partners_version';

	public $description = 'Plato partners';

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
			'technical_key',
			'name',
		];
	}
}
