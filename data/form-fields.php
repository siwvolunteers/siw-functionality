<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Standaard formuliervelden
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$data = [
	'voornaam' => [
		'slug'  => 'voornaam',
		'type'  => 'text',
		'label' => __( 'Voornaam', 'siw' ),
	],
	'achternaam' => [
		'slug'  => 'achternaam',
		'type'  => 'text',
		'label' => __( 'Achternaam', 'siw' ),
	],
	'emailadres' => [
		'slug'  => 'emailadres',
		'type'  => 'email',
		'label' => __( 'Emailadres', 'siw' ),
	],
	'telefoonnummer' => [
		'slug'     => 'telefoonnummer',
		'type'     => 'text',
		'label'    => __( 'Telefoonnummer', 'siw' ),
		'required' => false,
		'config'   => [
			'type_override' => 'tel',
		],
	],
	'geboortedatum'=> [
		'slug'   => 'geboortedatum',
		'type'   => 'text',
		'label'  => __( 'Geboortedatum', 'siw' ),
		'config' => [
			'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
		],
	],
	'geslacht' => [
		'slug'  => 'geslacht',
		'type'  => 'radio',
		'label' => __( 'Geslacht', 'siw' ),
		'config' => [
			'inline' => true,
			'option' => siw_get_genders(),
		]
	],
	'postcode' => [
		'slug'   => 'postcode',
		'type'   => 'text',
		'label'  => __( 'Postcode', 'siw' ),
		'config' => [
			'custom_class' => 'postcode',
			'placeholder'  => '1234 AB',
		],
	],
	'huisnummer' => [
		'slug'   => 'huisnummer',
		'type'   => 'text',
		'label'  => __( 'Huisnummer', 'siw' ),
		'config' => [
			'custom_class' => 'huisnummer',
		],
	],
	'straat' => [
		'slug'   => 'straat',
		'type'   => 'text',
		'label'  => __( 'Straat', 'siw' ),
		'config' => [
			'custom_class' => 'straat',
		],
	],
	'woonplaats' => [
		'slug'   => 'woonplaats',
		'type'   => 'text',
		'label'  => __( 'Woonplaats', 'siw' ),
		'config' => [
			'custom_class' => 'plaats',
		],
	],
	'cv' => [
		'slug'   => 'cv',
		'type'   => 'file',
		'label'  => __( 'Upload hier je CV (optioneel)', 'siw'),
		'config' => [
			'attach'     => true,
			'media_lib'  => false,
			'allowed'    => 'pdf,docx',
			'max_upload' => wp_max_upload_size(),
		],
		'required' => false,
	],
	'bekend_anders' => [
		'slug'       => 'bekend_anders',
		'label'      => __( 'Namelijk', 'siw' ),
		'hide_label' => true,
		'conditions' => [
			'type' => 'con_bekend_anders',
		],
	]
];

return $data;