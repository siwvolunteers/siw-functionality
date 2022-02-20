<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adresvelden voor checkout groepsprojecten
 *
 * @copyright 2019-2022 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'first_name' => [
		'class'       => ['form-row-first'],
		'priority'    => 10,
	],
	'last_name' => [
		'class'       => ['form-row-last'],
		'priority'    => 20,
	],
	'dob' => [
		'label'       => __( 'Geboortedatum', 'siw' ),
		'required'    => true,
		'type'        => 'date',
		'class'       => ['form-row-first', 'update_totals_on_change' ],
		'priority'    => 30,
	],
	'gender' => [
		'label'       => __( 'Geslacht', 'siw' ),
		'required'    => true,
		'type'        => 'radio',
		'options'     => siw_get_genders(),
		'class'       => ['form-row-last'],
		'priority'    => 40,
	],
	'student' => [
		'label'       => __( 'Ben je student?', 'siw' ),
		'type'        => 'radio',
		'class'       => ['form-row-first', 'update_totals_on_change'],
		'options'     => [
			'yes' => __( 'Ja', 'siw'),
			'no'  => __( 'Nee', 'siw' ),
		],
		'default'  => 'no',
		'priority'    => 50,
	],
	'nationality' => [
		'label'       => __( 'Nationaliteit', 'siw' ),
		'required'    => true,
		'type'        => 'select',
		'options'     => siw_get_nationalities(),
		'default'     => 'HOL',
		'class'       => ['form-row-last'],
		'priority'    => 60,
	],
];

return $data;