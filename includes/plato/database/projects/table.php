<?php declare(strict_types=1);

namespace SIW\Plato\Database\Projects;

class Table extends \BerlinDB\Database\Table {

	public $name = 'plato_projects';

	protected $db_version_key = 'plato_projects_version';

	protected $description = 'Plato projecten';

	protected $version = '1.0.0';

	protected $upgrades = [];

	protected function set_schema() {
		$this->schema = '
			id                          BIGINT(20) NOT NULL AUTO_INCREMENT,
			project_id                  CHAR(32) NOT NULL,
			code                        VARCHAR(255) NOT NULL,
			project_type                VARCHAR(4) NOT NULL,
			work                        VARCHAR(32) NOT NULL,
			start_date                  DATE NOT NULL,
			end_date                    DATE NOT NULL,
			name                        VARCHAR(255) NOT NULL,
			location                    TEXT,
			country                     CHAR(3) NOT NULL,
			region                      VARCHAR(255),
			languages                   VARCHAR(32) NOT NULL,
			participation_fee           FLOAT NOT NULL,
			participation_fee_currency  CHAR(3),
			min_age                     TINYINT NOT NULL,
			max_age                     TINYINT NOT NULL,
			disabled_vols               BOOL NOT NULL,
			numvol                      TINYINT NOT NULL,
			vegetarian                  BOOL NOT NULL,
			family                      BOOL,
			description                 TEXT,
			descr_partner               TEXT,
			descr_work                  TEXT,
			descr_accomodation_and_food TEXT,
			descr_location_and_leisure  TEXT,
			descr_requirements          TEXT,
			descr_appointement          TEXT,
			airport                     CHAR(3),
			train_bus_station           TEXT,
			numvol_m                    TINYINT NOT NULL,
			numvol_f                    TINYINT NOT NULL,
			max_vols_per_country        TINYINT NOT NULL,
			max_teenagers               TINYINT NOT NULL,
			max_national_vols           TINYINT NOT NULL,
			lat_project                 FLOAT,
			lng_project                 FLOAT,
			notes                       TEXT,
			lat_station                 FLOAT,
			lng_station                 FLOAT,
			bi_tri_multi                BOOL NOT NULL,
			ho_description              TEXT,
			project_summary             TEXT,
			accessibility               BOOL NOT NULL,
			last_update                 DATE NOT NULL,
			sdg_prj                     VARCHAR(32),
			url_prj_photo1              CHAR(32),
			url_prj_photo2              CHAR(32),
			url_prj_photo3              CHAR(32),
			url_prj_photo4              CHAR(32),
			url_prj_photo5              CHAR(32),
			url_prj_photo6              CHAR(32),
			url_prj_photo7              CHAR(32),
			url_prj_photo8              CHAR(32),
			url_prj_photo9              CHAR(32),
			cancelled                   BOOL NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY project_id (project_id)
		';
	}
}
