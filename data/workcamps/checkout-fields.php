<?php declare(strict_types=1);

use SIW\Data\Plato\Language;
use SIW\Data\Plato\Language_Skill_Level;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extra velden voor checkout groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$siw_data['emergency_contact'] = [
	'emergency_contact_name'  => [
		'label'    => __( 'Naam', 'siw' ),
		'type'     => 'text',
		'class'    => [ 'form-row-first' ],
		'required' => true,
	],
	'emergency_contact_phone' => [
		'label'    => __( 'Telefoonnummer', 'siw' ),
		'type'     => 'tel',
		'class'    => [ 'form-row-last' ],
		'required' => true,
		'validate' => [ 'phone' ],
	],
];

for ( $i = 1; $i <= 3; $i++ ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$siw_data['language'][ "language_{$i}" ] = [
		// translators: %d is geheel getal
		'label'    => sprintf( __( 'Taal %d', 'siw' ), $i ),
		'type'     => 'select',
		'class'    => [ 'form-row-first' ],
		'required' => 1 === $i,
		'options'  => [ '' => __( 'Selecteer een taal', 'siw' ) ] + Language::list(),
	];
	$siw_data['language'][ "language_{$i}_skill" ] = [
		// translators: %d is geheel getal
		'label'    => sprintf( __( 'Niveau taal %d', 'siw' ), $i ),
		'type'     => 'radio',
		'class'    => [ 'form-row-last' ],
		'required' => 1 === $i,
		'options'  => Language_Skill_Level::list( false ),
	];
}


$siw_data['info_for_partner'] = [
	'motivation'           => [
		'label'       => __( 'Motivation', 'siw' ),
		'type'        => 'textarea',
		'class'       => [ 'form-row-first' ],
		'required'    => true,
		'description' => __( 'Vul hier (in het Engels) in waarom je graag aan je gekozen project wil deelnemen.', 'siw' ),
	],
	'health_issues'        => [
		'label'       => __( 'Health issues', 'siw' ),
		'type'        => 'textarea',
		'class'       => [ 'form-row-last' ],
		'required'    => false,
		'description' => __( 'Heb je een allergie, gebruik je medicijnen of volg je een diëet, vul dat dan hier in (in het Engels).', 'siw' ),
	],
	'volunteer_experience' => [
		'label'       => __( 'Volunteer experience', 'siw' ),
		'type'        => 'textarea',
		'class'       => [ 'form-row-first' ],
		'required'    => false,
		'description' => __( 'Heb je eerder vrijwilligerswerk gedaan? Beschrijf dat dan hier (in het Engels).', 'siw' ),
	],
	'together_with'        => [
		'label'       => __( 'Together with', 'siw' ),
		'type'        => 'text',
		'class'       => [ 'form-row-last' ],
		'required'    => false,
		'description' => __( 'Wil je graag met iemand aan een project deelnemen. Vul zijn of haar naam dan hier in.', 'siw' ),
	],
];

return $siw_data;
