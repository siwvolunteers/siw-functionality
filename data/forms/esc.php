<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van ESC-formulier
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */

$args = [
	'name' => __( 'ESC', 'siw' ),
];

$processors = [];

$intro = [
	__( 'Start snel jouw eigen ESC avontuur!', 'siw' ),
	__( 'Als je onderstaand formulier invult nemen wij zo snel mogelijk contact met je op.', 'siw' ),
];

$pages = [];

$fields[0] =[
	[
		[
			'slug'  => 'voornaam',
			'type'  => 'text',
			'label' => __( 'Voornaam', 'siw' ),
		],
		[
			'slug'  => 'achternaam',
			'type'  => 'text',
			'label' => __( 'Achternaam', 'siw' ),
		],
	],
	[
		[
			'slug'   => 'geboortedatum',
			'type'   => 'text',
			'label'  => __( 'Geboortedatum', 'siw' ),
			'config' => [
				'placeholder' => __( 'dd-mm-jjjj', 'siw' ),
			],
		],
		[
			'slug'  => 'geslacht',
			'type'  => 'radio',
			'label' => __( 'Geslacht', 'siw' ),
			'config' => [
				'inline' => 1,
				'option' => siw_get_genders(),
			]
		],
	],
	[
		[
			'slug'     => 'telefoonnummer',
			'type'     => 'text',
			'label'    => __( 'Telefoonnummer', 'siw' ),
			'config'   => [
				'type_override' => 'tel',
			],
		],
		[
			'slug'  => 'emailadres',
			'type'  => 'email',
			'label' => __( 'Emailadres', 'siw' ),
		],
	],
	[
		[
			'slug'   => 'postcode',
			'type'   => 'text',
			'label'  => __( 'Postcode', 'siw' ),
			'config' => [
				'custom_class' => 'postcode',
				'placeholder'  => '1234 AB',
			],
		],
		[
			'slug'   => 'huisnummer',
			'type'   => 'text',
			'label'  => __( 'Huisnummer', 'siw' ),
			'config' => [
				'custom_class' => 'huisnummer',
			],
		]
	],
	[
		[
			'slug'   => 'straat',
			'type'   => 'text',
			'label'  => __( 'Straat', 'siw' ),
			'config' => [
				'custom_class' => 'straat',
			],
		],
		[
			'slug'   => 'woonplaats',
			'type'   => 'text',
			'label'  => __( 'Woonplaats', 'siw' ),
			'config' => [
				'custom_class' => 'plaats',
			],
		],
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
		[
			'slug'   => 'cv',
			'type'   => 'file',
			'label'  => __( 'Upload hier je CV (optioneel)', 'siw'),
			'config' => [
				'attach'     => 1,
				'media_lib'  => 0,
				'allowed'    => 'pdf,docx',
				'max_upload' => wp_max_upload_size(),
			],
		],
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
	'recipient_email' => '%emailadres%',
];

$notification = [
	'subject'  => 'Aanmelding ESC',
	'message'  => 'Via de website is onderstaande ESC-aanmelding binnengekomen:', 
	'reply_to' => '%emailadres%',
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'esc_email',
];