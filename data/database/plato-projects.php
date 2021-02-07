<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van Plato project
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'name'        => 'project_id',
		'type'        => 'CHAR',
		'length'      => 32,
		'primary_key' => true,
	],
	[
		'name'        => 'code',
		'type'        => 'VARCHAR',
		'length'      => 255,
	],
	[
		'name'        => 'project_type',
		'type'        => 'CHAR',
		'length'      => 3,
	],
	[
		'name'        => 'work',
		'type'        => 'VARCHAR',
		'length'      => 32,
	],
	[
		'name'        => 'start_date',
		'type'        => 'DATE',
	],
	[
		'name'        => 'end_date',
		'type'        => 'DATE',
	],
	[
		'name'        => 'name',
		'type'        => 'VARCHAR',
		'length'      => 255,
	],
	[
		'name'        => 'location',
		'type'        => 'VARCHAR',
		'length'      => 255,
		'nullable'    => true,
	],
	[
		'name'        => 'country',
		'type'        => 'CHAR',
		'length'      => 3,
	],
	[
		'name'        => 'region',
		'type'        => 'VARCHAR',
		'length'      => 255,
		'nullable'    => true,
	],
	[
		'name'    => 'languages',
		'type'    => 'VARCHAR',
		'length'   => 32,
	],
	[
		'name'    => 'participation_fee',
		'type'    => 'FLOAT',
	],
	[
		'name'     => 'participation_fee_currency',
		'type'     => 'CHAR',
		'length'   => 3,
		'nullable' => true,
	],
	[
		'name'     => 'min_age',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'max_age',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'disabled_vols',
		'type'     => 'BOOL',
	],
	[
		'name'     => 'numvol',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'vegetarian',
		'type'     => 'BOOL',
	],
	[
		'name'     => 'family',
		'type'     => 'BOOL',
	],
	[
		'name'     => 'description',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'descr_partner',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'descr_work',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'descr_accomodation_and_food',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'descr_location_and_leisure',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'descr_requirements',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'descr_appointement',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'airport',
		'type'     => 'CHAR',
		'length'   => 3,
		'nullable' => true,
	],
	[
		'name'     => 'train_bus_station',
		'type'     => 'VARCHAR',
		'length'   => 255,
		'nullable' => true,
	],
	[
		'name'     => 'numvol_m',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'numvol_f',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'max_vols_per_country',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'max_teenagers',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'max_national_vols',
		'type'     => 'TINYINT',
	],
	[
		'name'     => 'lat_project',
		'type'     => 'FLOAT',
		'nullable' => true,
	],
	[
		'name'     => 'lng_project',
		'type'     => 'FLOAT',
		'nullable' => true,
	],
	[
		'name'     => 'notes',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'lat_station',
		'type'     => 'FLOAT',
		'nullable' => true,
	],
	[
		'name'     => 'lng_station',
		'type'     => 'FLOAT',
		'nullable' => true,
	],
	[
		'name'     => 'bi_tri_multi',
		'type'     => 'BOOL',
	],
	[
		'name'     => 'ho_description',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'project_summary',
		'type'     => 'TEXT',
		'nullable' => true,
	],
	[
		'name'     => 'accessibility',
		'type'     => 'BOOL',
	],
	[
		'name'     => 'last_update',
		'type'     => 'DATE',
	],
	[
		'name'     => 'sdg_prj',
		'type'     => 'VARCHAR',
		'length'   => 32,
		'nullable' => true,
	],
];
return $data;