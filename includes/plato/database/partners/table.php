<?php declare(strict_types=1);

namespace SIW\Plato\Database\Partners;

class Table extends \BerlinDB\Database\Table {

	public $name = 'plato_partners';

	protected $db_version_key = 'plato_partners_version';

	protected $description = 'Plato partners';

	protected $version = '1.0.0';

	protected $upgrades = [];

	protected function set_schema() {
		$this->schema = '
			id             BIGINT(20) NOT NULL AUTO_INCREMENT,
			technical_key  CHAR(36) NOT NULL,
			name           MEDIUMTEXT NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY technical_key (technical_key)
		';
	}
}
