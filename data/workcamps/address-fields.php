<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adresvelden voor checkout groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
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
		'type'        => 'text',
		'class'       => ['form-row-first'],
		'input_class' => ['dateNL'],
		'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
		'priority'    => 30,
	],
	'gender' => [
		'label'       => __( 'Geslacht', 'siw' ),
		'required'    => true,
		'type'        => 'radio',
		'options'     => siw_get_genders(),
		'class'       => ['form-row-last'],
		'label_class' => ['radio-label'],
		'priority'    => 40,
	],
	'postcode' => [
		'class'       => ['form-row-first'],
		'input_class' => ['postalcodeNL'],
		'placeholder' => '1234 AB',
		'priority'    => 65,
	],
	'housenumber' => [
		'label'       => __( 'Huisnummer', 'siw' ),
		'required'    => true,
		'type'        => 'text',
		'class'       => ['form-row-last'],
		'priority'    => 70,
	],
	'address_1' => [
		'label'       => __( 'Straat', 'siw' ),
		'class'       => ['form-row-first'],
		'placeholder' => '',
		'priority'    => 75,
	],
	'city' => [
		'class'       => ['form-row-last'],
		'priority'    => 80,
	],
	'country' => [
		'class'       => ['form-row-first', 'country', 'select'],
		'label_class' => ['select-label'],
		'priority'    => 85,
	],
	'nationality' => [
		'label'       => __( 'Nationaliteit', 'siw' ),
		'required'    => true,
		'type'        => 'select',
		'options'     => siw_get_nationalities(),
		'default'     => 'HOL',
		'class'       => ['form-row-last', 'select'],
		'label_class' => ['select-label'],
		'priority'    => 90,
	],
];

return $data;