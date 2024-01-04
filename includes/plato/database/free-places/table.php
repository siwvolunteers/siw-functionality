<?php declare(strict_types=1);

namespace SIW\Plato\Database\Free_Places;

class Table extends \BerlinDB\Database\Table {

	public $name = 'plato_free_places';

	protected $db_version_key = 'plato_free_places_version';

	protected $description = 'Plato FPL';

	protected $version = '1.0.0';

	protected $upgrades = [];

	protected function set_schema() {
		$this->schema = '
			id                        BIGINT(20) NOT NULL AUTO_INCREMENT,
			project_id                CHAR(32) NOT NULL,
			code                      VARCHAR(255) NOT NULL,
			start_date                DATE NOT NULL,
			end_date                  DATE NOT NULL,
			numvol                    TINYINT NOT NULL,
			free_m                    TINYINT NOT NULL,
			free_f                    TINYINT NOT NULL,
			free_teen                 TINYINT NOT NULL,
			reserved                  TINYINT NOT NULL,
			no_more_from              VARCHAR(255),
			remarks                   TEXT,
			last_update               DATE,
			file_identifier_infosheet CHAR(32),
			PRIMARY KEY (id),
			UNIQUE KEY project_id (project_id)
		';
	}
}
