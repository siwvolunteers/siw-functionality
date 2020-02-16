<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van ESC-formulier
 * 
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 */

$args = [
	'name'            => __( 'ESC', 'siw' ),
	'postcode_lookup' => true,
];

$processors = [];

$intro = [
	__( 'Start snel jouw eigen ESC avontuur!', 'siw' ),
	__( 'Als je onderstaand formulier invult nemen wij zo snel mogelijk contact met je op.', 'siw' ),
];

$pages = [];

$fields[0] =[
	[
		'voornaam',
		'achternaam',
	],
	[
		'geboortedatum',
		'geslacht',
	],
	[
		[
			'slug'     => 'telefoonnummer',
			'required' => true,
		],
		'emailadres',
	],
	[
		'postcode',
		'huisnummer',
	],
	[
		'straat',
		'woonplaats',
	],
	[
		[
			'slug'   => 'motivatie',
			'type'   => 'paragraph',
			'label'  => __( 'Waarom wil je graag aan een ESC-project deelnemen?', 'siw' ),
			'config' => [
				'rows' => 7,
			]
		],
		[
			'slug'   => 'periode',
			'type'   => 'paragraph',
			'label'  => __( 'In welke periode zou je graag een ESC project willen doen?', 'siw' ),
			'config' => [
				'rows' => 7,
			]
		]
	],
	[
		'cv',
		[
			'slug'   => 'bekend',
			'type'   => 'checkbox',
			'label'  => __( 'Hoe heb je van SIW gehoord?', 'siw' ),
			'config' => [
				'option' => [
					'google'           => __( 'Google', 'siw' ),
					'website'          => __( 'Website SIW', 'siw' ),
					'social_media'     => __( 'Social Media', 'siw' ),
					'familie_vrienden' => __( 'Familie / vrienden', 'siw' ),
					'infodag'          => __( 'SIW Infodag', 'siw' ),
					'nji'              => __( 'NJI ESC infomiddag/avond', 'siw' ),
					'anders'           => __( 'Anders', 'siw' ),
				],
			],
		],
		[
			'slug'       => 'bekend_anders',
			'type'       => 'text',
			'label'      => __( 'Namelijk', 'siw' ),
			'hide_label' => 1,
			'condition'  => [
				'type'     => 'show',
				'groups'   => [
					[
						[
							'field'   => 'bekend',
							'compare' => 'is',
							'value'   => 'anders',
						],
					],
				],
			],
			'same_cell' => true,
		],
	]
];

/** Bevestigingsmail */
$confirmation = [
	'subject' => __( 'Bevestiging aanmelding', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor je ESC-aanmelding.', 'siw' ) . SPACE .
		__( 'Onderaan deze mail staan de gegevens die je hebt ingevuld.', 'siw' ) . BR .
		__( 'We nemen zo snel mogelijk contact met je op om in een gesprek verder met je kennis te maken en op zoek te gaan naar een leuk en geschikt project!', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
];

$notification = [
	'subject'  => 'Aanmelding ESC',
	'message'  => 'Via de website is onderstaande ESC-aanmelding binnengekomen:', 
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'primary_email' => 'emailadres',
];