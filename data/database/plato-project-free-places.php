<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van Plato FPL
 *
 * @copyright 2021 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	[
		'name'         => 'project_id',
		'type'         => 'CHAR',
		'length'       => 32,
		'primary_key'  => true,
	],
	[
		'name'         => 'code',
		'type'         => 'VARCHAR',
		'length'       => 255,
	],
	[
		'name'         => 'start_date',
		'type'         => 'DATE',
	],
	[
		'name'         => 'end_date',
		'type'         => 'DATE',
	],
	[
		'name'         => 'numvol',
		'type'         => 'TINYINT',
	],
	[
		'name'         => 'free_m',
		'type'         => 'TINYINT',
	],
	[
		'name'         => 'free_f',
		'type'         => 'TINYINT',
	],
	[
		'name'         => 'free_teen',
		'type'         => 'TINYINT',
	],
	[
		'name'         => 'reserved',
		'type'         => 'TINYINT',
	],
	[
		'name'         => 'no_more_from',
		'type'         => 'VARCHAR',
		'length'       => 255,
		'nullable'     => true,
	],
	[
		'name'         => 'remarks',
		'type'         => 'TEXT',
		'nullable'     => true,
	],
	[
		'name'         => 'last_update',
		'type'         => 'DATE',
	],
	[
		'name'         => 'file_identifier_infosheet',
		'type'         => 'CHAR',
		'length'       => 32,
		'nullable'     => true,
		'xml_property' => 'url_infosheet'
	],
];
return $data;