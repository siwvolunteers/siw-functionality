<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gegevens van Algemeen contactformulier
 * 
 * @package   SIW\Forms
 * @author    Maarten Bruna
 * @copyright 2019 SIW Internationale Vrijwilligersprojecten
 * */

$language = SIW_i18n::get_current_language();
$project_options = [];
$projects = siw_get_option( 'dutch_projects' );
foreach ( $projects as $project ) {
	$slug = sanitize_title( $project['code'] );
	$name = $project["name_{$language}"];

	$project_options[ $slug ] = $project["name_{$language}"];
}


$args = [
	'name' => __( 'Projectbegeleider NP', 'siw' ),
];

$processors = [];

$intro = [
	__( 'De Nederlandse vrijwilligersprojecten vinden plaats in de zomermaanden.', 'siw' ),
	__( 'We zijn hiervoor altijd op zoek naar projectbegeleiders.', 'siw' ),
	__( 'Meld je aan via onderstaand formulier en geef aan naar welk project je voorkeur uitgaat.', 'siw' ),
	__( 'Vervolgens ontvang je een uitnodiging voor een kennismakingsgesprek.', 'siw' ),
	__( 'In dit gesprek hopen wij erachter te komen wat je verwachtingen en kwaliteiten zijn.', 'siw' ),
];

$pages = [];

$fields[0] = [
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
				'inline' => true,
				'option' => siw_get_genders(),
			]
		],
	],
	[
		[
			'slug'  => 'emailadres',
			'type'  => 'email',
			'label' => __( 'Emailadres', 'siw' ),
		],
		[
			'slug'     => 'telefoonnummer',
			'type'     => 'text',
			'label'    => __( 'Telefoonnummer', 'siw' ),
			'required' => false,
			'config'   => [
				'type_override' => 'tel',
			],
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
			'label'  => __( 'Waarom zou je graag een begeleider willen worden voor de Nederlandse vrijwilligersprojecten?', 'siw' ),
			'config' => [
				'rows' => 7,
			]
		],
		[
			'slug'   => 'voorkeur',
			'type'   => 'checkbox',
			'label'  => __( 'Heb je een voorkeur om een bepaald Nederlands vrijwilligersproject te begeleiden?', 'siw' ),
			'config' => [
				'option' => $project_options,
			]
		]
	],
	[
		[
			'slug'   => 'bekend',
			'type'   => 'checkbox',
			'label'  => __( 'Hoe heb je van SIW gehoord?', 'siw' ),
			'config' => [
				'option' => [
					'google'           => __( 'Google', 'siw' ),
					'social_media'     => __( 'Social Media', 'siw' ),
					'familie_vrienden' => __( 'Familie / vrienden', 'siw' ),
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
		[
			'slug'     => 'opmerkingen',
			'type'     => 'paragraph',
			'label'    => __( 'Overige opmerkingen?', 'siw' ),
			'required' => false,
		]
	],
];

/** Bevestigingsmail TODO: tekst conditioneel van datum maken*/
$confirmation = [
	'subject' => __( 'Bevestiging aanmelding', 'siw' ),
	'message' =>
		sprintf( __( 'Beste %s,', 'siw' ), '%voornaam%' ) . BR2 .
		__( 'Bedankt voor jouw aanmelding.', 'siw') . SPACE .
		__( 'Wat leuk dat je interesse hebt om projectbegeleider te worden voor de Nederlandse vrijwilligersprojecten.', 'siw' ) . SPACE .
		__( 'Een creatieve uitdaging die je nooit meer zal vergeten!', 'siw' ) . SPACE .
		__( 'Zoals oud-projectbegeleider Diederik (project in Friesland) het omschreef:', 'siw' ) . BR .
		'<span style="font-style:italic">"'.
		__( 'Het is ontzettend leerzaam om met zoveel verschillende mensen om te gaan, iedereen gemotiveerd te houden en te zorgen dat iedereen zich op zijn gemak voelt.', 'siw' ) . SPACE .
		__( 'Daarnaast zie je hoe de groep zich ontwikkelt, een prachtig proces om van zo dichtbij mee te mogen maken.', 'siw' ) .
		'"</span>' . BR2 .
		'<span style="font-weight:bold">' .
		__( 'Hoe gaat het nu verder?', 'siw' ) .
		'</span>' . BR .
		__( 'Wij werven doorgaans in de maanden maart tot en met mei projectbegeleiders om de zomerprojecten te begeleiden.', 'siw' ) . SPACE .
		__( 'Mocht jij je in deze periode hebben aangemeld, dan zullen wij contact met je opnemen.', 'siw' ) . SPACE .
		__( 'Ligt jouw aanmelding buiten onze wervingsperiode? Geen probleem.', 'siw' ) . SPACE .
		__( 'Wij voegen jouw aanmelding toe aan onze database voor een volgend zomerseizoen.', 'siw' ),
	'recipient_name'  => '%voornaam% %achternaam%',
	'recipient_email' => '%emailadres%',
];

$notification = [
	'subject'  => 'Aanmelding projectbegeleider',
	'message'  => 'Via de website is onderstaande aanmelding voor begeleider NP binnengekomen:',
	'reply_to' => '%emailadres%',
];

return [
	'args'          => $args,
	'intro'         => $intro,
	'pages'         => $pages,
	'fields'        => $fields,
	'confirmation'  => $confirmation,
	'notification'  => $notification,
	'email_option'  => 'camp_leader_email',
];