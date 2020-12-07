<?php declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extra velden voor checkout groepsprojecten
 *
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$volunteer_languages = siw_get_languages( 'volunteer', 'plato' );
$languages[''] = __( 'Selecteer een taal', 'siw' );
foreach ( $volunteer_languages as $language ) {
	$languages[ $language->get_plato_code() ] = $language->get_name();
}

$language_skill = siw_get_language_skill_levels();

//TODO: loop van maken
$data['language'] = [
	'language1' => [
		'label'       => __( 'Taal 1', 'siw' ),
		'type'        => 'select',
		'class'       => ['form-row-first', 'select'],
		'label_class' => ['select-label'],
		'required'    => true,
		'options'     => $languages
	],
	'language1Skill' => [
		'label'       => __( 'Niveau taal 1', 'siw' ),
		'type'        => 'radio',
		'class'       => ['form-row-last'],
		'label_class' => ['radio-label'],
		'required'    => true,
		'options'     => $language_skill
	],
	'language2' => [
		'label'       => __( 'Taal 2', 'siw' ),
		'type'        => 'select',
		'class'       => ['form-row-first', 'select'],
		'label_class' => ['select-label'],
		'required'    => false,
		'options'     => $languages
	],
	'language2Skill' => [
		'label'       => __( 'Niveau taal 2', 'siw' ),
		'type'        => 'radio',
		'class'       => ['form-row-last'],
		'label_class' => ['radio-label'],
		'required'    => false,
		'options'     => $language_skill
	],
	'language3' => [
		'label'       => __( 'Taal 3', 'siw' ),
		'type'        => 'select',
		'class'       => ['form-row-first', 'select'],
		'label_class' => ['select-label'],
		'required'    => false,
		'options'     => $languages
	],
	'language3Skill' => [
		'label'       => __( 'Niveau taal 3', 'siw' ),
		'type'        => 'radio',
		'class'       => ['form-row-last'],
		'label_class' => ['radio-label'],
		'required'    => false,
		'options'     => $language_skill
	],
];
$data['emergency_contact'] = [
	'emergencyContactName' =>[
		'label'    => __( 'Naam', 'siw' ),
		'type'     => 'text',
		'class'    => ['form-row-first'],
		'required' => true,
	],
	'emergencyContactPhone' =>[
		'label'    => __( 'Telefoonnummer', 'siw' ),
		'type'     => 'tel',
		'class'    => ['form-row-last'],
		'required' => true,
		'validate' => ['phone'],
	],
];
$data['info_for_partner'] = [
	'motivation' => [
		'label'       => __( 'Motivation', 'siw' ),
		'type'        => 'textarea',
		'class'       => ['form-row-first'],
		'required'    => true,
		'description' => __( 'Vul hier (in het Engels) in waarom je graag aan je gekozen project wil deelnemen.', 'siw' ),
	],
	'healthIssues'=>[
		'label'       => __( 'Health issues', 'siw' ),
		'type'        => 'textarea',
		'class'       => ['form-row-last'],
		'required'    => false,
		'description' => __( 'Heb je een allergie, gebruik je medicijnen of volg je een diëet, vul dat dan hier in (in het Engels).', 'siw' ),
	],
	'volunteerExperience' => [
		'label'       => __( 'Volunteer experience', 'siw' ),
		'type'        => 'textarea',
		'class'       => ['form-row-first'],
		'required'    => false,
		'description' => __( 'Heb je eerder vrijwilligerswerk gedaan? Beschrijf dat dan hier (in het Engels).', 'siw' ),
	],
	'togetherWith'    => [
		'label'       => __( 'Together with', 'siw' ),
		'type'        => 'text',
		'class'       => ['form-row-last'],
		'required'    => false,
		'description' => __( 'Wil je graag met iemand aan een project deelnemen. Vul zijn of haar naam dan hier in.', 'siw' ),
	],
];

return $data;