<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use SIW\Properties;

/**
 * Tarieven van groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'regulier' => [
		'name'          => 'regulier',
		'regular_price' => Properties::WORKCAMP_FEE_REGULAR,
		'sale_price'    => Properties::WORKCAMP_FEE_REGULAR_SALE
	],
	'student' => [
		'name'          => 'student / <18',
		'regular_price' => Properties::WORKCAMP_FEE_STUDENT,
		'sale_price'    => Properties::WORKCAMP_FEE_STUDENT_SALE
	]
];

return $data;