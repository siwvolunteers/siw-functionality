<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data van Plato Projectafbeelding
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
		'name'        => 'image_id',
		'type'        => 'TINYINT',
		'primary_key' => true,
	],
	[
		'name'        => 'file_identifier',
		'type'        => 'CHAR',
		'length'      => 32,
	],

];
return $data;